<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientUserBalance;
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
                        ->with([
                            'items.service',
                            'items.location',
                        ])
                        ->orderBy('date', 'desc');
                }
            ])
            ->firstOrFail();

        $totalDebt = PatientUserBalance::where('patient_id', $patient->id)
            ->sum('balance');

        return view('admin.print.service', [
            'patient'   => $patient,
            'totalDebt' => $totalDebt,
        ]);
    }

    public function printPrescription($id)
    {
        $prescription = Prescription::with(['patient.user', 'user', 'items'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('admin.print.prescription', compact('prescription'));
    }
}
