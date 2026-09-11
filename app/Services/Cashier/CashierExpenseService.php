<?php

namespace App\Services\Cashier;

use App\Models\CashierLedger;
use App\Models\UserCashBalance;
use Illuminate\Support\Facades\DB;

class CashierExpenseService
{
    public function handle(int $userId, float $amount, ?string $description = null): void
    {
        DB::transaction(function () use ($userId, $amount, $description) {
            $userCashBalance = UserCashBalance::where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            if (!$userCashBalance) {
                throw new \Exception('Kassa balansı tapılmadı');
            }

            if ($amount > (float) $userCashBalance->balance) {
                throw new \Exception('Kassadakı məbləğdən artıq xərc yazmaq olmaz');
            }

            $userCashBalance->decrement('balance', $amount);

            CashierLedger::create([
                'cashier_id' => $userId,
                'user_id'  => $userId,
                'type'       => 'adjust_out',
                'method'     => 'cash',
                'amount'     => $amount,
                'note'       => $description,
                'created_at' => now(),
            ]);
        });
    }
}
