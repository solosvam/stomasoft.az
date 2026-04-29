<?php

namespace App\Services\Cashier;

use App\Models\CashierLedger;
use App\Models\DoctorCashBalance;
use Illuminate\Support\Facades\DB;

class CashierIncomeService
{
    public function handle(int $userId, float $amount, ?string $description = null): void
    {
        DB::transaction(function () use ($userId, $amount, $description) {
            $doctorCashBalance = DoctorCashBalance::where('doctor_id', $userId)
                ->lockForUpdate()
                ->first();

            if (!$doctorCashBalance) {
                $doctorCashBalance = DoctorCashBalance::create([
                    'doctor_id' => $userId,
                    'balance'   => 0,
                ]);
            }

            $doctorCashBalance->increment('balance', $amount);

            CashierLedger::create([
                'cashier_id' => $userId,
                'doctor_id'  => $userId,
                'type'       => 'adjust_in',
                'method'     => 'cash',
                'amount'     => $amount,
                'note'       => $description,
                'created_at' => now(),
            ]);
        });
    }
}
