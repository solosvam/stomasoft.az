<?php

namespace App\Services\Cashier;

use App\Models\CashierLedger;
use App\Models\DoctorCashBalance;
use Illuminate\Support\Facades\DB;

class CashierExpenseService
{
    public function handle(int $userId, float $amount, ?string $description = null): void
    {
        DB::transaction(function () use ($userId, $amount, $description) {
            $doctorCashBalance = DoctorCashBalance::where('doctor_id', $userId)
                ->lockForUpdate()
                ->first();

            if (!$doctorCashBalance) {
                throw new \Exception('Kassa balansı tapılmadı');
            }

            if ($amount > (float) $doctorCashBalance->balance) {
                throw new \Exception('Kassadakı məbləğdən artıq xərc yazmaq olmaz');
            }

            $doctorCashBalance->decrement('balance', $amount);

            CashierLedger::create([
                'cashier_id' => $userId,
                'doctor_id'  => $userId,
                'type'       => 'adjust_out',
                'method'     => 'cash',
                'amount'     => $amount,
                'note'       => $description,
                'created_at' => now(),
            ]);
        });
    }
}
