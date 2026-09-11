<?php

namespace App\Services\Technician;

use App\Models\Services;
use App\Models\Technician\TechnicianDoctor;
use App\Models\Technician\TechnicianDoctorBalance;
use App\Models\Technician\TechnicianDoctorLedger;

class TechnicianDoctorInfoService
{
    public function getData(int $doctorId, int $userId): array
    {
        $doctor = $this->getDoctor($doctorId, $userId);

        return [
            'doctor' => $doctor,
            'services' => Services::all(),
            'rows' => $this->getLedger($doctorId, $userId),
            'balance' => $this->getBalance($doctorId, $userId),
        ];
    }

    private function getDoctor(int $doctorId, int $userId): TechnicianDoctor
    {
        return TechnicianDoctor::with([
            'jobs' => function ($query) {
                $query->orderByDesc('id');
            },
            'jobs.items.service',
            'jobs.items.locations.location',
        ])
            ->where('id', $doctorId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    private function getBalance(int $doctorId, int $userId): float
    {
        return (float) TechnicianDoctorBalance::where('doctor_id', $doctorId)
            ->where('user_id', $userId)
            ->value('balance');
    }

    private function getLedger(int $doctorId, int $userId)
    {
        return TechnicianDoctorLedger::where('doctor_id', $doctorId)
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(function ($row) {

                $row->signed_amount = $row->type === 'payment'
                    ? -abs((float) $row->amount)
                    : abs((float) $row->amount);

                return $row;
            });
    }
}
