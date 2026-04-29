<?php

namespace App\Services\Crm;

use App\Models\PatientDoctorBalance;
use App\Models\PatientLedger;
use App\Models\PatientServiceSession;
use App\Models\PatientServiceSessionItems;
use Illuminate\Support\Facades\DB;

class UpdatePatientSessionService
{
    public function handle(int $sessionId, int $doctorId, array $data): int
    {
        return DB::transaction(function () use ($sessionId, $doctorId, $data) {
            $session = PatientServiceSession::with('items')
                ->where('id', $sessionId)
                ->where('user_id', $doctorId)
                ->firstOrFail();

            if ((int) $session->status !== 1) {
                throw new \Exception('Bitmiş xidməti dəyişmək olmaz');
            }

            $patientId = (int) $session->patient_id;
            $oldTotal  = (float) $session->total_cost;

            $toothIds = collect($data['tooth_id'] ?? [])
                ->map(fn ($x) => (int) $x)
                ->filter(fn ($x) => $x > 0)
                ->unique()
                ->values()
                ->all();

            $items = $data['items'] ?? [];

            $balance = PatientDoctorBalance::firstOrCreate(
                [
                    'patient_id' => $patientId,
                    'doctor_id'  => $doctorId,
                ],
                [
                    'balance' => 0,
                ]
            );

            PatientServiceSessionItems::where('session_id', $session->id)->delete();

            PatientLedger::where('session_id', $session->id)
                ->where('patient_id', $patientId)
                ->where('doctor_id', $doctorId)
                ->where('type', 'service')
                ->delete();

            $newTotal = 0;

            foreach ($items as $item) {
                $serviceId = (int) ($item['service_id'] ?? 0);
                $price     = abs((float) ($item['price'] ?? 0));
                $percent   = min(100, abs((float) ($item['percent'] ?? 0)));
                $priceNet  = abs((float) ($item['price_net'] ?? 0));
                $note      = $item['note'] ?? null;

                if ($serviceId <= 0 || $price < 0 || $priceNet < 0) {
                    continue;
                }

                foreach ($toothIds as $toothId) {
                    PatientServiceSessionItems::create([
                        'session_id' => $session->id,
                        'service_id' => $serviceId,
                        'tooth_id'   => $toothId,
                        'note'       => $note,
                        'price'      => $price,
                        'percent'    => $percent,
                        'price_net'  => $priceNet,
                    ]);

                    PatientLedger::create([
                        'patient_id' => $patientId,
                        'doctor_id'  => $doctorId,
                        'session_id' => $session->id,
                        'type'       => 'service',
                        'amount'     => $priceNet,
                        'note'       => $note ?: ($data['comment'] ?? null),
                        'created_at' => now(),
                    ]);

                    $newTotal += $priceNet;
                }
            }

            $session->update([
                'note'       => $data['comment'] ?? null,
                'total_cost' => $newTotal,
            ]);

            $diff = $newTotal - $oldTotal;

            if ($diff > 0) {
                $balance->increment('balance', $diff);
            } elseif ($diff < 0) {
                $balance->decrement('balance', abs($diff));
            }

            return $patientId;
        });
    }
}
