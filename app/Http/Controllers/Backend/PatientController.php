<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientDoctorBalance;
use App\Models\PatientFiles;
use App\Models\PatientServiceSessionItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\PatientCreateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patients = Patient::where('user_id',$user->id)->orderByDesc('id')->get();

        return view('admin.patient.list',[
            'patients'  => $patients
        ]);
    }

    public function debtors()
    {
        $debtors = PatientDoctorBalance::with(['patient','doctor'])
            ->where('balance','>',0)
            ->whereHas('patient', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderByDesc('balance')
            ->paginate(20);
        $total = $debtors->sum('balance');
        return view('admin.patient.debtors', compact('debtors','total'));
    }

    public function create(PatientCreateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $raw = trim($data['bday']);

        try {
            $data['bday'] = Carbon::parse($raw)->format('Y-m-d');
        } catch (\Exception $e) {
            $normalized = str_replace(['/', '-', ' '], '.', $raw);
            $data['bday'] = Carbon::createFromFormat('d.m.Y', $normalized)->format('Y-m-d');
        }

        $patient = Patient::create($data);
        return redirect()->route('admin.crm.info',$patient->id);
    }

    public function edit($id)
    {
        $patient = Patient::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('admin.patient.edit', [
            'patient' => $patient
        ]);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $data = $request->only([
            'name',
            'surname',
            'fin',
            'address',
            'sex',
            'mobile',
            'bday',
            'comment'
        ]);

        if (!empty($data['bday'])) {
            try {
                $data['bday'] = \Carbon\Carbon::parse($data['bday'])->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $normalized = str_replace(['/', '-', ' '], '.', trim($data['bday']));
                    $data['bday'] = \Carbon\Carbon::createFromFormat('d.m.Y', $normalized)->format('Y-m-d');
                } catch (\Exception $e) {
                    $data['bday'] = null;
                }
            }
        }

        $patient->update($data);

        return redirect()->route('admin.crm.info',$id)->with('success','Düzəliş edildi');
    }

    public function workedTeeth(Patient $patient)
    {
        $worked = PatientServiceSessionItems::query()
            ->whereHas('session', fn($q) => $q->where('patient_id', $patient->id))
            ->distinct()
            ->orderBy('tooth_id')
            ->pluck('tooth_id')
            ->values();

        return response()->json([
            'worked' => $worked
        ]);
    }

    public function fileUpload(Request $request, $patient_id)
    {
        if(!$request->hasFile('file')){
            return response()->json(['status'=>false,'message'=>'file yoxdur'],422);
        }

        $file = $request->file('file');

        $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        $file->move(public_path('backend/uploads/patients/'.$patient_id), $name);

        $patientFile = PatientFiles::create([
            'patient_id' => $patient_id,
            'file' => $name
        ]);

        $count = PatientFiles::where('patient_id', $patient_id)->count();

        return response()->json([
            'status' => true,
            'file_id' => $patientFile->id,
            'file' => $name,
            'url' => asset('backend/uploads/patients/'.$patient_id.'/'.$name),
            'date' => now()->format('d.m.Y H:i'),
            'count' => $count,
        ]);
    }

    public function fileDelete($id)
    {
        $file = PatientFiles::findOrFail($id);

        $path = public_path('backend/uploads/patients/' . $file->patient_id . '/' . $file->file);

        if (file_exists($path)) {
            unlink($path);
        }

        $file->delete();

        return response()->json(['status' => true]);
    }

    public function listData()
    {
        $user = Auth::user();
        $patients = Patient::where('user_id',$user->id)->orderByDesc('id')->get()->map(function ($p) {
            return [
                'id'       => $p->id,
                'fullname' => $p->name . ' ' . $p->surname,
                'gender'   => $p->sex == 1 ? 'Kişi' : 'Qadın',
                'age'      => \Carbon\Carbon::parse($p->bday)->age,
                'mobile'   => $p->mobile,
                'balance'  => $p->totalbalance.' AZN',
                'comment'  => $p->comment
            ];
        });


        return response()->json($patients);
    }

    public function activeServices()
    {
        $patients = Patient::where('user_id', auth()->id())
            ->whereHas('sessions', function ($q) {
                $q->where('status', 1);
            })
            ->with(['sessions' => function ($q) {
                $q->where('status', 1);
            }])
            ->orderByDesc('id')
            ->get();

        return view('admin.patient.active_services', [
            'patients' => $patients
        ]);
    }
}
