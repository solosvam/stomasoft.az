<?php

namespace App\Services\Crm;

use App\Models\PatientServiceSession;
use App\Models\Services;

class EditPatientSessionService
{
    public function getData(int $sessionId, int $doctorId): array
    {
        $session = PatientServiceSession::with([
            'patient',
            'items.service',
        ])
            ->where('id', $sessionId)
            ->where('user_id', $doctorId)
            ->first();

        if (!$session) {
            throw new \Exception('Xidmət sessiyası tapılmadı');
        }

        if ((int) $session->status !== 1) {
            throw new \Exception('Bitmiş xidməti edit etmək olmaz');
        }

        $sessionToothIds = $session->items
            ->pluck('tooth_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $groupedItems = $session->items
            ->groupBy(function ($item) {
                return implode('|', [
                    $item->service_id,
                    number_format((float) $item->price, 2, '.', ''),
                    number_format((float) $item->percent, 2, '.', ''),
                    number_format((float) $item->price_net, 2, '.', ''),
                    trim((string) $item->note),
                ]);
            })
            ->map(function ($group) {
                $first = $group->first();

                return (object) [
                    'service_id' => $first->service_id,
                    'price'      => $first->price,
                    'percent'    => $first->percent,
                    'price_net'  => $first->price_net,
                    'note'       => $first->note,
                ];
            })
            ->values();

        return [
            'session'         => $session,
            'patient'         => $session->patient,
            'services'        => Services::orderBy('name')->get(),
            'sessionToothIds' => $sessionToothIds,
            'groupedItems'    => $groupedItems,
        ];
    }
}
