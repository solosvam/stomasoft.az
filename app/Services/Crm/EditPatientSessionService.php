<?php

namespace App\Services\Crm;

use App\Models\PatientServiceSession;
use App\Models\ServiceLocation;
use App\Models\Services;

class EditPatientSessionService
{
    public function getData(
        int $sessionId,
        int $doctorId,
        string $locationMap
    ): array {
        $session = PatientServiceSession::with([
            'patient',
            'items.service',
            'items.location',
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

        $sessionLocationIds = $session->items
            ->pluck('location_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
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

        $locations = ServiceLocation::where('type', $locationMap)
            ->orderBy('id')
            ->get()
            ->keyBy('code');

        return [
            'session'            => $session,
            'patient'            => $session->patient,
            'services'           => Services::orderBy('name')->get(),
            'locations'          => $locations,
            'sessionLocationIds' => $sessionLocationIds,
            'groupedItems'       => $groupedItems,
        ];
    }
}
