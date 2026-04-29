<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AddPatientServiceRequest;
use App\Http\Requests\Backend\PatientPayRequest;
use App\Http\Requests\Backend\UpdatePatientSessionRequest;
use App\Models\Patient;
use App\Models\PatientDoctorBalance;
use App\Models\PatientLedger;
use App\Models\PatientServiceSession;
use App\Models\PatientServiceSessionItems;
use App\Models\Prescription;
use App\Models\PrescriptionItems;
use App\Services\Crm\AddPatientServiceService;
use App\Services\Crm\EditPatientSessionService;
use App\Services\Crm\PatientInfoService;
use App\Services\Crm\PatientPaymentService;
use App\Services\Crm\UpdatePatientSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function index()
    {
        return view('admin.crm.index');
    }
    public function info($id, PatientInfoService $patientInfoService)
    {
        $data = $patientInfoService->getData((int) $id, auth()->id());

        return view('admin.crm.patient', $data);
    }


    public function addService(AddPatientServiceRequest $request, $id, AddPatientServiceService $addPatientServiceService): RedirectResponse {
        $addPatientServiceService->handle(
            (int) $id,
            auth()->id(),
            $request->validated()
        );

        return back()->with('success', 'Xidmət əlavə edildi !');
    }

    public function pay(PatientPayRequest $request,$id, PatientPaymentService $patientPaymentService): RedirectResponse {
        try {
            $patientPaymentService->handle((int) $id, auth()->id(), $request->validated());

            return back()->with('success', 'ödəmə qeydə alındı');
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => $e->getMessage()])->withInput();
        }
    }

    public function finishSession($id)
    {
        $session = PatientServiceSession::findOrFail($id);

        $session->status = 0;
        $session->save();
        return redirect()->back()->with('success', 'xidmət bitirildi');
    }


    public function editSession($id, EditPatientSessionService $editPatientSessionService): View|RedirectResponse
    {
        try {
            $data = $editPatientSessionService->getData((int) $id, auth()->id());

            return view('admin.crm.session_edit', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateSession(UpdatePatientSessionRequest $request, $id, UpdatePatientSessionService $updatePatientSessionService): RedirectResponse {
        $patientId = $updatePatientSessionService->handle(
            (int) $id,
            auth()->id(),
            $request->validated()
        );

        return redirect()
            ->route('admin.crm.info', $patientId)
            ->with('success', 'Xidmət yeniləndi!');
    }

    public function deleteSession($id): RedirectResponse
    {
        $session = PatientServiceSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $patientId = (int) $session->patient_id;

        try {
            DB::transaction(function () use ($session) {
                if ((int) $session->status !== 1) {
                    throw new \Exception('Bitmiş xidməti silmək olmaz');
                }

                $patientId = (int) $session->patient_id;
                $doctorId  = (int) auth()->id();
                $oldTotal  = (float) $session->total_cost;

                $balance = PatientDoctorBalance::firstOrCreate(
                    [
                        'patient_id' => $patientId,
                        'doctor_id'  => $doctorId,
                    ],
                    [
                        'balance' => 0,
                    ]
                );

                PatientLedger::where('session_id', $session->id)
                    ->where('patient_id', $patientId)
                    ->where('doctor_id', $doctorId)
                    ->where('type', 'service')
                    ->delete();

                PatientServiceSessionItems::where('session_id', $session->id)->delete();

                $session->delete();

                if ($oldTotal > 0) {
                    $newBalance = max(0, (float)$balance->balance - $oldTotal);
                    $balance->update(['balance' => $newBalance]);
                }
            });

            return redirect()->route('admin.crm.info', $patientId)->with('success', 'Xidmət silindi!');
        } catch (\Exception $e) {
            return redirect()->route('admin.crm.info', $patientId)->with('error', $e->getMessage());
        }
    }

    public function addPrescription(Request $request, $id)
    {
        $patient = Patient::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'medicine_name'   => 'required|array|min:1',
            'medicine_name.*' => 'required|string|max:255',
            'dose'            => 'nullable|array',
            'dose.*'          => 'nullable|string|max:255',
            'usage_text'      => 'nullable|array',
            'usage_text.*'    => 'nullable|string|max:255',
        ], [
            'medicine_name.required'   => 'Dərman adı boş ola bilməz',
            'medicine_name.*.required' => 'Dərman adı boş ola bilməz',
            'dose.required'            => 'Doza boş ola bilməz',
            'dose.*.required'          => 'Doza boş ola bilməz',
            'usage_text.required'      => 'İstifadə qaydası boş ola bilməz',
            'usage_text.*.required'    => 'İstifadə qaydası boş ola bilməz',
        ]);

        $prescription = Prescription::create([
            'doctor_id'  => auth()->id(),
            'patient_id' => $patient->id,
        ]);

        foreach ($request->medicine_name as $key => $medicineName) {
            PrescriptionItems::create([
                'prescription_id' => $prescription->id,
                'medicine_name'   => $medicineName,
                'dose'            => $request->dose[$key] ?? null,
                'usage_text'      => $request->usage_text[$key] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Resept əlavə edildi');
    }

    public function toothServices($id, Request $request)
    {
        $toothId = (int) $request->tooth_id;

        $sessions = PatientServiceSession::with([
            'items' => function ($q) use ($toothId) {
                $q->where('tooth_id', $toothId)->with('service');
            }
        ])
            ->where('patient_id', $id)
            ->where('user_id', auth()->id())
            ->whereHas('items', function ($q) use ($toothId) {
                $q->where('tooth_id', $toothId);
            })
            ->orderByDesc('id')
            ->get();

        return view('admin.crm.partial.tooth_services', compact('sessions', 'toothId'));
    }
}
