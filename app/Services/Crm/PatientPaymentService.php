<?php

namespace App\Services\Crm;

use App\Models\CashierLedger;
use App\Models\UserCashBalance;
use App\Models\Patient;
use App\Models\PatientDepositLedger;
use App\Models\PatientUserBalance;
use App\Models\PatientUserDeposit;
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

            $doctorId = (int) ($data['user_id'] ?? 0);
            $amount   = round((float) $data['amount'], 2);
            $method   = $data['method'];
            $note     = $data['note'] ?? null;

            if ($doctorId <= 0) {
                throw new \Exception('həkim seçilməlidir');
            }

            $patientDoctorBalance = PatientUserBalance::where('patient_id', $patient->id)
                ->where('user_id', $doctorId)
                ->lockForUpdate()
                ->first();

            if (!$patientDoctorBalance || (float) $patientDoctorBalance->balance <= 0) {
                throw new \Exception('bu həkim üzrə borc yoxdur');
            }

            $currentBalance = round((float) $patientDoctorBalance->balance, 2);

            if ($amount > $currentBalance) {
                throw new \Exception('məbləğ bu həkimin borcundan çox ola bilməz');
            }

            if ($method === 'deposit') {
                $deposit = PatientUserDeposit::where('patient_id', $patient->id)
                    ->where('user_id', $doctorId)
                    ->lockForUpdate()
                    ->first();

                if (!$deposit || (float) $deposit->deposit <= 0) {
                    throw new \Exception('bu həkim üzrə depozit yoxdur');
                }

                $currentDeposit = round((float) $deposit->deposit, 2);

                if ($amount > $currentDeposit) {
                    throw new \Exception('ödəniş məbləği depozitdən çox ola bilməz');
                }

                $patientDoctorBalance->decrement('balance', $amount);
                $deposit->decrement('deposit', $amount);

                $deposit->update([
                    'updated_at' => now(),
                ]);

                $this->createPatientLedger($patient->id, $doctorId, $cashierId, $amount, $method, $note);

                $this->createDepositLedger($patient->id, $doctorId, $cashierId, $amount, $note);

                return;
            }

            $patientDoctorBalance->decrement('balance', $amount);

            $this->createPatientLedger($patient->id, $doctorId, $cashierId, $amount, $method, $note);
            $this->createCashierLedger($patient->id, $doctorId, $cashierId, $amount, $method, $note);
            $this->incrementUserCash($doctorId, $amount);
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
            'user_id'  => $doctorId,
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
            'user_id'  => $doctorId,
            'patient_id' => $patientId,
            'partner_id' => null,
            'type'       => 'patient_payment',
            'method'     => $method,
            'amount'     => $amount,
            'note'       => $note,
            'created_at' => now(),
        ]);
    }

    private function createDepositLedger(
        int $patientId,
        int $doctorId,
        int $cashierId,
        float $amount,
        ?string $note
    ): void {
        PatientDepositLedger::create([
            'patient_id' => $patientId,
            'user_id'  => $doctorId,
            'cashier_id' => $cashierId,
            'type'       => 'payment',
            'amount'     => $amount,
            'note'       => $note,
            'created_at' => now(),
        ]);
    }

    private function incrementUserCash(int $doctorId, float $amount): void
    {
        $userCashBalance = UserCashBalance::firstOrCreate(
            ['user_id' => $doctorId],
            ['balance' => 0]
        );

        $userCashBalance->increment('balance', $amount);
    }
}
