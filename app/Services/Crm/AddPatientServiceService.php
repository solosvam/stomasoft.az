<?php

namespace App\Services\Crm;

use App\Models\Patient;
use App\Models\PatientDoctorBalance;
use App\Models\PatientLedger;
use App\Models\PatientServiceSession;
use App\Models\PatientServiceSessionItems;
use Illuminate\Support\Facades\DB;

class AddPatientServiceService
{
    public function handle(int $patientId, int $doctorId, array $data): void
    {
        DB::transaction(function () use ($patientId, $doctorId, $data) {

            $patient = Patient::where('id', $patientId)
                ->where('user_id', $doctorId)
                ->firstOrFail();

            $session = $this->createSession(
                $patient->id,
                $doctorId,
                $data['comment'] ?? null
            );

            $balance = $this->getOrCreateDoctorBalance(
                $patient->id,
                $doctorId
            );

            $totalCost = 0;

            $locationIds = $data['location_id'] ?? [];
            $serviceIds  = $data['service_id'] ?? [];
            $prices      = $data['price'] ?? [];
            $percents    = $data['percent'] ?? [];
            $priceNets   = $data['price_net'] ?? [];
            $notes       = $data['note'] ?? [];

            foreach ($serviceIds as $i => $serviceId) {

                $serviceId = (int) ($serviceId ?? 0);
                $price     = abs((float) ($prices[$i] ?? 0));
                $percent   = min(100, abs((float) ($percents[$i] ?? 0)));
                $priceNet  = abs((float) ($priceNets[$i] ?? 0));
                $note      = $notes[$i] ?? null;

                if ($serviceId <= 0) {
                    continue;
                }

                foreach ($locationIds as $locationId) {

                    $locationId = (int) $locationId;

                    if ($locationId <= 0) {
                        continue;
                    }

                    $this->createSessionItem(
                        $session->id,
                        $serviceId,
                        $locationId,
                        $note,
                        $price,
                        $percent,
                        $priceNet
                    );

                    $this->createPatientLedger(
                        $patient->id,
                        $doctorId,
                        $session->id,
                        $priceNet,
                        $note ?? ($data['comment'] ?? null)
                    );

                    $balance->increment('balance', $priceNet);

                    $totalCost += $priceNet;
                }
            }

            $session->update([
                'total_cost' => $totalCost,
            ]);
        });
    }

    private function createSession(
        int $patientId,
        int $doctorId,
        ?string $comment
    ): PatientServiceSession {
        return PatientServiceSession::create([
            'patient_id' => $patientId,
            'user_id'    => $doctorId,
            'note'       => $comment,
            'total_cost' => 0,
        ]);
    }

    private function getOrCreateDoctorBalance(
        int $patientId,
        int $doctorId
    ): PatientDoctorBalance {
        return PatientDoctorBalance::firstOrCreate(
            [
                'patient_id' => $patientId,
                'doctor_id'  => $doctorId,
            ],
            [
                'balance' => 0,
            ]
        );
    }

    private function createSessionItem(
        int $sessionId,
        int $serviceId,
        int $locationId,
        ?string $note,
        float $price,
        float $percent,
        float $priceNet
    ): void {
        PatientServiceSessionItems::create([
            'session_id'  => $sessionId,
            'service_id'  => $serviceId,
            'location_id' => $locationId,
            'note'        => $note,
            'price'       => $price,
            'percent'     => $percent,
            'price_net'   => $priceNet,
        ]);
    }

    private function createPatientLedger(
        int $patientId,
        int $doctorId,
        int $sessionId,
        float $amount,
        ?string $note
    ): void {
        PatientLedger::create([
            'patient_id' => $patientId,
            'doctor_id'  => $doctorId,
            'session_id' => $sessionId,
            'type'       => 'service',
            'amount'     => $amount,
            'note'       => $note,
            'created_at' => now(),
        ]);
    }
}
