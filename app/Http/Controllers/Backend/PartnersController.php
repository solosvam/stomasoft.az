<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PartnerCreateRequest;
use App\Models\CashierLedger;
use App\Models\DoctorCashBalance;
use App\Models\PartnerDoctorBalance;
use App\Models\PartnerDoctorPatientBalance;
use App\Models\PartnerLedger;
use Illuminate\Http\Request;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartnersController extends Controller
{
    public function index()
    {
        $partners = Partner::where('user_id',auth()->id())
            ->withSum('doctorBalances as balance', 'balance')
            ->orderByDesc('balance')
            ->get();

        return view('admin.partners.list', compact('partners'));
    }

    public function add(PartnerCreateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        Partner::create($data);

        return redirect()->back()->with('success', 'Partnyor əlavə edildi !');
    }

    public function edit($id)
    {
        $partner = Partner::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('admin.partners.edit',[
            'partner' => $partner
        ]);
    }

    public function delete($id)
    {
        $partner = Partner::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $hasLedger = PartnerLedger::where('partner_id', $partner->id)->exists();

        if ($hasLedger) {
            return redirect()->back()->with('error', 'Bu partnyor silinə bilməz, əməliyyatları var');
        }

        $partner->delete();

        return redirect()->back()->with('success', 'Partnyor silindi');
    }

    public function update(PartnerCreateRequest $request,$id)
    {
        Partner::findOrFail($id)->update($request->validated());

        return redirect()->route('admin.partners.list')->with('success', 'Düzəliş olundu !');
    }

    public function doctorBalance($partnerId)
    {
        $userId = auth()->id();

        $partner = Partner::findOrFail($partnerId);
        $doctorId = $partner->user_id;

        $patientRows = PartnerDoctorPatientBalance::with('patient')
            ->where('partner_id', $partnerId)
            ->where('doctor_id', $doctorId)
            ->where('balance', '>', 0)
            ->whereHas('patient', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();

        $partnerDoctorBalance = (float) PartnerDoctorBalance::where('partner_id', $partnerId)
            ->where('doctor_id', $doctorId)
            ->value('balance');

        $patientBalanceTotal = (float) PartnerDoctorPatientBalance::where('partner_id', $partnerId)
            ->where('doctor_id', $doctorId)
            ->sum('balance');

        $generalBalance = round($partnerDoctorBalance - $patientBalanceTotal, 2);

        $rows = collect($patientRows);

        if ($generalBalance > 0) {
            $rows->push((object) [
                'patient_id' => null,
                'patient' => null,
                'partner' => $partner,
                'doctor' => $partner->user,
                'balance' => $generalBalance,
                'is_general' => true,
            ]);
        }

        return view('admin.partners.doctor_balance', compact('rows','partner','doctorId'));
    }

    public function patientLedger($partnerId,$doctorId,$patientId = null)
    {
        $query = PartnerLedger::where('partner_id',$partnerId)
            ->where('doctor_id',$doctorId);

        if ($patientId) {
            $query->where('patient_id',$patientId);
        } else {
            $query->whereNull('patient_id');
        }

        $rows = $query->orderByDesc('id')->get();

        $paymentAfterIds = [];

        foreach ($rows as $row) {

            if($row->type != 'purchase'){
                continue;
            }

            $paymentAfterIds[$row->id] = PartnerLedger::where('partner_id',$row->partner_id)
                ->where('doctor_id',$row->doctor_id)
                ->where('patient_id',$row->patient_id)
                ->where('id','>',$row->id)
                ->where('type','payment')
                ->exists();
        }

        return view(
            'admin.partners.patient_ledger',
            compact('rows','patientId','paymentAfterIds')
        );
    }


    public function purchase(Request $request, $id)
    {
        $doctorId  = Auth::id();
        $partnerId = (int)$request->partner_id;
        $patientId = (int)$id;
        $amount    = abs((float)$request->amount);

        PartnerLedger::create([
            'partner_id' => $partnerId,
            'doctor_id'  => $doctorId,
            'patient_id' => $patientId,
            'type'       => 'purchase',
            'amount'     => $amount,
            'note'       => $request->note,
        ]);

        $bal = PartnerDoctorBalance::firstOrCreate(
            ['partner_id' => $partnerId, 'doctor_id' => $doctorId],
            ['balance' => 0]
        );
        $bal->increment('balance', $amount);

        $pbal = PartnerDoctorPatientBalance::firstOrCreate(
            ['partner_id' => $partnerId, 'doctor_id' => $doctorId, 'patient_id' => $patientId],
            ['balance' => 0]
        );
        $pbal->increment('balance', $amount);

        return back()->with('success', 'Əməliyyat tamamlandı');
    }

    public function payPartnerPatient(Request $request, $partnerId, $doctorId, $patientId)
    {
        $patientId = (int)$patientId;
        $patientId = $patientId === 0 ? null : $patientId;

        $data = $request->validate([
            'amount' => ['required','numeric','min:0.01'],
            'note'   => ['nullable','string','max:255'],
        ]);

        $amount    = round((float)$data['amount'], 2);
        $note      = $data['note'] ?? null;
        $cashierId = Auth::id();

        return DB::transaction(function () use ($partnerId,$doctorId,$patientId,$amount,$note,$cashierId) {

            $pdb = PartnerDoctorBalance::where('partner_id', $partnerId)
                ->where('doctor_id', $doctorId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($patientId) {
                $pdpb = PartnerDoctorPatientBalance::where('partner_id', $partnerId)
                    ->where('doctor_id', $doctorId)
                    ->where('patient_id', $patientId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $currentDebt = round((float)$pdpb->balance, 2);
            } else {
                $patientDebtSum = round((float) PartnerDoctorPatientBalance::where('partner_id', $partnerId)
                    ->where('doctor_id', $doctorId)
                    ->sum('balance'), 2);

                $currentDebt = round((float)$pdb->balance, 2) - $patientDebtSum;
            }

            if ($currentDebt <= 0) {
                return back()->withErrors(['amount' => 'Borc yoxdur']);
            }

            if ($amount > $currentDebt) {
                return back()->withErrors(['amount' => 'Məbləğ borcdan çox ola bilməz']);
            }

            $dcb = DoctorCashBalance::where('doctor_id', $doctorId)->lockForUpdate()->first();
            $cashBal = round((float)($dcb->balance ?? 0), 2);

            if ($cashBal < $amount) {
                return back()->withErrors(['amount' => 'Həkimin kassasında yetərli məbləğ yoxdur']);
            }

            $dcb->decrement('balance', $amount);
            $pdb->decrement('balance', $amount);

            if ($patientId) {
                $pdpb->decrement('balance', $amount);
            }

            PartnerLedger::create([
                'partner_id' => $partnerId,
                'doctor_id'  => $doctorId,
                'patient_id' => $patientId,
                'type'       => 'payment',
                'amount'     => $amount,
                'cashier_id' => $cashierId,
                'note'       => $note,
                'created_at' => now(),
            ]);

            CashierLedger::create([
                'cashier_id' => $cashierId,
                'doctor_id'  => $doctorId,
                'patient_id' => $patientId,
                'partner_id' => $partnerId,
                'type'       => 'partner_payment',
                'method'     => 'cash',
                'amount'     => $amount,
                'note'       => $note,
                'created_at' => now(),
            ]);

            return back()->with('success', 'partnyora ödəniş edildi');
        });
    }

    public function buyitem(Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'amount'     => 'required|numeric|min:0.01',
            'note'       => 'nullable|string|max:255',
        ],[
            'partner_id.required' => 'Partnyor seç',
            'partner_id.exists'   => 'Partnyor tapılmadı',
            'amount.required'     => 'Məbləğ yaz',
            'amount.numeric'      => 'Məbləğ rəqəm olmalıdır',
            'amount.min'          => 'Məbləğ 0-dan böyük olmalıdır',
        ]);

        $doctorId  = Auth::id();
        $partnerId = (int) $request->partner_id;
        $amount    = abs((float) $request->amount);

        PartnerLedger::create([
            'partner_id' => $partnerId,
            'doctor_id'  => $doctorId,
            'patient_id' => null,
            'type'       => 'purchase',
            'amount'     => $amount,
            'note'       => $request->note,
            'created_at' => now(),
        ]);

        $balance = PartnerDoctorBalance::firstOrCreate(
            [
                'partner_id' => $partnerId,
                'doctor_id'  => $doctorId,
            ],
            [
                'balance'    => 0,
                'updated_at' => now(),
            ]
        );

        $balance->increment('balance', $amount);
        $balance->updated_at = now();
        $balance->save();

        return back()->with('success', 'Alış əlavə edildi');
    }

    public function deletePurchase($ledgerId)
    {
        $doctorId = auth()->id();

        $ledger = PartnerLedger::where('id', $ledgerId)
            ->where('doctor_id', $doctorId)
            ->where('type', 'purchase')
            ->firstOrFail();

        DB::transaction(function () use ($ledger) {

            PartnerDoctorBalance::where('partner_id', $ledger->partner_id)
                ->where('doctor_id', $ledger->doctor_id)
                ->decrement('balance', $ledger->amount);

            PartnerDoctorPatientBalance::where('partner_id', $ledger->partner_id)
                ->where('doctor_id', $ledger->doctor_id)
                ->where('patient_id', $ledger->patient_id)
                ->decrement('balance', $ledger->amount);

            $ledger->delete();
        });

        return redirect()->route('admin.partners.list')->with('success', 'Alış silindi və balans geri qaytarıldı');
    }

}
