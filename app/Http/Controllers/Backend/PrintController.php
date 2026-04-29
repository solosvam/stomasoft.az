<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientDoctorBalance;
use App\Models\Prescription;

class PrintController extends Controller
{
    public function printService($id)
    {
        $patient = Patient::where('id', $id)
            ->where('user_id', auth()->id())
            ->with([
                'sessions' => function ($q) {
                    $q->where('status', 1)
                        ->with(['items.service'])
                        ->orderBy('date', 'desc');
                }
            ])
            ->firstOrFail();

        $totalDebt = PatientDoctorBalance::where('patient_id', $patient->id)->sum('balance');

        return view('admin.print.service', [
            'patient'   => $patient,
            'totalDebt' => $totalDebt,
        ]);
    }

    public function printPrescription($id)
    {
        $prescription = Prescription::with(['patient.doctor', 'doctor', 'items'])
            ->where('id', $id)
            ->where('doctor_id', auth()->id())
            ->firstOrFail();

        return view('admin.print.prescription', compact('prescription'));
    }
}
