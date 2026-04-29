<?php

namespace App\Services\Crm;

use App\Models\CashierLedger;
use App\Models\DoctorCashBalance;
use App\Models\Patient;
use App\Models\PatientDoctorBalance;
use App\Models\PatientLedger;
use Illuminate\Support\Facades\DB;

class PatientPaymentService
{
    public function handle(int $patientId, int $cashierId, array $data): void
    {
        DB::transaction(function () use ($patientId, $cashierId, $data) {
            $patient = Patient::where('id', $patientId)
                ->where('user_id', $cashierId)
                ->firstOrFail();

            $doctorId = (int) ($data['doctor_id'] ?? 0);
            $amount   = round((float) $data['amount'], 2);
            $method   = $data['method'];
            $note     = $data['note'] ?? null;

            if ($doctorId <= 0) {
                throw new \Exception('həkim seçilməlidir');
            }

            $patientDoctorBalance = PatientDoctorBalance::where('patient_id', $patient->id)
                ->where('doctor_id', $doctorId)
                ->lockForUpdate()
                ->first();

            if (!$patientDoctorBalance || (float) $patientDoctorBalance->balance <= 0) {
                throw new \Exception('bu həkim üzrə borc yoxdur');
            }

            $currentBalance = round((float) $patientDoctorBalance->balance, 2);

            if ($amount > $currentBalance) {
                throw new \Exception('məbləğ bu həkimin borcundan çox ola bilməz');
            }

            $patientDoctorBalance->decrement('balance', $amount);

            $this->createPatientLedger($patient->id, $doctorId, $cashierId, $amount, $method, $note);
            $this->createCashierLedger($patient->id, $doctorId, $cashierId, $amount, $method, $note);
            $this->incrementDoctorCash($doctorId, $amount);
        });
    }

    private function createPatientLedger(
        int $patientId,
        int $doctorId,
        int $cashierId,
        float $amount,
        string $method,
        ?string $note
    ): void {
        PatientLedger::create([
            'patient_id' => $patientId,
            'doctor_id'  => $doctorId,
            'session_id' => null,
            'type'       => 'payment',
            'amount'     => $amount,
            'method'     => $method,
            'cashier_id' => $cashierId,
            'note'       => $note,
            'created_at' => now(),
        ]);
    }

    private function createCashierLedger(
        int $patientId,
        int $doctorId,
        int $cashierId,
        float $amount,
        string $method,
        ?string $note
    ): void {
        CashierLedger::create([
            'cashier_id' => $cashierId,
            'doctor_id'  => $doctorId,
            'patient_id' => $patientId,
            'partner_id' => null,
            'type'       => 'patient_payment',
            'method'     => $method,
            'amount'     => $amount,
            'note'       => $note,
            'created_at' => now(),
        ]);
    }

    private function incrementDoctorCash(int $doctorId, float $amount): void
    {
        $doctorCashBalance = DoctorCashBalance::firstOrCreate(
            ['doctor_id' => $doctorId],
            ['balance' => 0]
        );

        $doctorCashBalance->increment('balance', $amount);
    }
}
