<?php

namespace App\Services\Cashier;

use App\Models\CashierLedger;
use App\Models\UserCashBalance;
use Illuminate\Support\Facades\DB;

class CashierIncomeService
{
    public function handle(int $userId, float $amount, ?string $description = null): void
    {
        DB::transaction(function () use ($userId, $amount, $description) {
            $userCashBalance = UserCashBalance::where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            if (!$userCashBalance) {
                $userCashBalance = UserCashBalance::create([
                    'user_id' => $userId,
                    'balance'   => 0,
                ]);
            }

            $userCashBalance->increment('balance', $amount);

            CashierLedger::create([
                'cashier_id' => $userId,
                'user_id'  => $userId,
                'type'       => 'adjust_in',
                'method'     => 'cash',
                'amount'     => $amount,
                'note'       => $description,
                'created_at' => now(),
            ]);
        });
    }
}
