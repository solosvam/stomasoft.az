<?php

namespace App\Services\Crm;

use App\Models\Partner;
use App\Models\PartnerDoctorPatientBalance;
use App\Models\PartnerLedger;
use App\Models\Patient;
use App\Models\PatientDoctorBalance;
use App\Models\PatientLedger;
use App\Models\Reservation;
use App\Models\Services;
use Carbon\Carbon;

class PatientInfoService
{
    public function getData(int $patientId, int $userId): array
    {
        $patient = $this->getPatient($patientId, $userId);

        return array_merge(
            ['patient' => $patient],
            $this->getBaseData($patient, $userId),
            $this->getFinancialData($patient, $userId),
            $this->getLedgerData($patient),
            $this->getDoctorTotals($patient),
            $this->getPartnerPatientBalances($patient, $userId),
            ['slots' => $this->generateSlots()]
        );
    }

    private function getPatient(int $patientId, int $userId): Patient
    {
        return Patient::where('id', $patientId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    private function getBaseData(Patient $patient, int $userId): array
    {
        return [
            'services' => Services::all(),
            'partners' => Partner::where('user_id', $userId)->get(),
            'reservations' => Reservation::where('patient_id', $patient->id)->get(),
        ];
    }

    private function getFinancialData(Patient $patient, int $userId): array
    {
        $patientServiceTotal = (float) PatientLedger::where('patient_id', $patient->id)
            ->where('doctor_id', $userId)
            ->where('type', 'service')
            ->sum('amount');

        $partnerPurchaseTotal = (float) PartnerLedger::where('patient_id', $patient->id)
            ->where('doctor_id', $userId)
            ->where('type', 'purchase')
            ->sum('amount');

        return [
            'patientServiceTotal' => $patientServiceTotal,
            'partnerPurchaseTotal' => $partnerPurchaseTotal,
        ];
    }

    private function getLedgerData(Patient $patient): array
    {
        $rows = PatientLedger::with('doctor')
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(function ($row) {
                $row->signed_amount = $row->type === 'payment'
                    ? -abs((float) $row->amount)
                    : abs((float) $row->amount);

                return $row;
            });

        return [
            'rows' => $rows,
        ];
    }

    private function getDoctorTotals(Patient $patient): array
    {
        return [
            'doctorTotals' => PatientDoctorBalance::with('doctor')
                ->where('patient_id', $patient->id)
                ->where('balance', '>', 0)
                ->orderByDesc('balance')
                ->get(),
        ];
    }

    private function getPartnerPatientBalances(Patient $patient, int $userId): array
    {
        return [
            'partnerPatientBalances' => PartnerDoctorPatientBalance::with('partner')
                ->where('patient_id', $patient->id)
                ->where('doctor_id', $userId)
                ->where('balance', '>', 0)
                ->orderByDesc('balance')
                ->get(),
        ];
    }

    private function generateSlots(): array
    {
        $slots = [];
        $start = Carbon::createFromTime(10, 0);
        $end   = Carbon::createFromTime(20, 0);

        while ($start <= $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(30);
        }

        return $slots;
    }
}
