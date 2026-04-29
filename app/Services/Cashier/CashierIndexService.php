<?php

namespace App\Services\Cashier;

use App\Models\CashierLedger;
use App\Models\DoctorCashBalance;
use Carbon\Carbon;

class CashierIndexService
{
    public function getData(int $userId, ?string $dates = null): array
    {
        [$from, $to] = $this->resolveDateRange($dates);

        $doctorCash = DoctorCashBalance::with('doctor')
            ->where('doctor_id', $userId)
            ->first();

        $incomeLogs = CashierLedger::with(['doctor', 'patient'])
            ->where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::INCOME_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('id')
            ->get();

        $expenseLogs = CashierLedger::with(['doctor', 'partner'])
            ->where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::EXPENSE_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('id')
            ->get();

        $incomeSummary = CashierLedger::where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::INCOME_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("
                COALESCE(SUM(CASE WHEN method = 'cash' THEN amount END), 0) as total_cash,
                COALESCE(SUM(CASE WHEN method = 'c2c' THEN amount END), 0) as total_c2c,
                COALESCE(SUM(CASE WHEN method = 'pos' THEN amount END), 0) as total_posterminal,
                COALESCE(SUM(amount), 0) as total_all
            ")
            ->first();

        $expenseSummary = CashierLedger::where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::EXPENSE_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("
                COALESCE(SUM(amount), 0) as total_all
            ")
                    ->first();

        return compact('doctorCash', 'incomeLogs', 'expenseLogs', 'incomeSummary','expenseSummary');
    }

    private function resolveDateRange(?string $dates): array
    {
        if ($dates) {
            [$from, $to] = explode(':', $dates);

            return [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ];
        }

        return [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ];
    }

    public function getDataForChart(int $userId, ?string $dates = null): array
    {
        [$from, $to] = $this->resolveDateRange($dates);

        $doctorCash = DoctorCashBalance::with('doctor')
            ->where('doctor_id', $userId)
            ->first();

        $incomeLogs = CashierLedger::with(['doctor', 'patient'])
            ->where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::INCOME_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('id')
            ->paginate(20, ['*'], 'income_page')
            ->appends(['dates' => $dates]);

        $expenseLogs = CashierLedger::with(['doctor', 'partner'])
            ->where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::EXPENSE_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('id')
            ->paginate(20, ['*'], 'expense_page')
            ->appends(['dates' => $dates]);

        $incomeSummary = CashierLedger::where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::INCOME_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("
            COALESCE(SUM(CASE WHEN method = 'cash' THEN amount END), 0) as total_cash,
            COALESCE(SUM(CASE WHEN method = 'c2c' THEN amount END), 0) as total_c2c,
            COALESCE(SUM(CASE WHEN method = 'pos' THEN amount END), 0) as total_posterminal,
            COALESCE(SUM(amount), 0) as total_all
        ")
            ->first();

        $expenseSummary = CashierLedger::where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::EXPENSE_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("
            COALESCE(SUM(amount), 0) as total_all
        ")
            ->first();

        $profitSummary = (float)$incomeSummary->total_all - (float)$expenseSummary->total_all;

        $period = \Carbon\CarbonPeriod::create($from->copy()->startOfDay(), $to->copy()->startOfDay());

        $dailyLabels = [];
        $dailyIncome = [];
        $dailyExpense = [];

        foreach ($period as $date) {
            $day = $date->format('Y-m-d');
            $dailyLabels[] = $date->format('d.m');

            $income = CashierLedger::where('cashier_id', $userId)
                ->whereIn('type', CashierLedger::INCOME_TYPES)
                ->whereDate('created_at', $day)
                ->sum('amount');

            $expense = CashierLedger::where('cashier_id', $userId)
                ->whereIn('type', CashierLedger::EXPENSE_TYPES)
                ->whereDate('created_at', $day)
                ->sum('amount');

            $dailyIncome[] = (float) $income;
            $dailyExpense[] = (float) $expense;
        }

        return compact(
            'doctorCash',
            'incomeLogs',
            'expenseLogs',
            'incomeSummary',
            'expenseSummary',
            'profitSummary',
            'dailyLabels',
            'dailyIncome',
            'dailyExpense',
            'from',
            'to'
        );
    }
}
