<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CashierLedger;
use App\Models\PartnerDoctorBalance;
use App\Models\PartnerDoctorPatientBalance;
use App\Models\PartnerLedger;
use App\Models\PatientDoctorBalance;
use App\Models\PatientLedger;
use App\Models\PatientServiceSession;
use App\Models\PatientServiceSessionItems;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->dates) {
            [$from, $to] = explode(':', $request->dates);

            $from = Carbon::parse($from)->startOfDay();
            $to   = Carbon::parse($to)->endOfDay();
        } else {
            $from = now()->startOfMonth();
            $to   = now()->endOfMonth();
        }

        $userId = Auth::id();

        $statistics = PatientServiceSessionItems::query()
            ->select(
                'patient_service_session_items.service_id',
                'services.name as service_name',
                DB::raw('COUNT(patient_service_session_items.id) as total_count'),
                DB::raw('SUM(patient_service_session_items.price) as total_income')
            )
            ->join('patient_service_session', 'patient_service_session.id', '=', 'patient_service_session_items.session_id')
            ->join('services', 'services.id', '=', 'patient_service_session_items.service_id')
            ->where('patient_service_session.user_id', $userId)
            ->whereBetween('patient_service_session.date', [$from, $to])
            ->groupBy('patient_service_session_items.service_id', 'services.name')
            ->inRandomOrder()
            ->get();

        $lineLabels = $statistics->pluck('service_name')->values();
        $lineCounts = $statistics->pluck('total_count')->map(fn($item) => (int) $item)->values();
        $lineIncome = $statistics->pluck('total_income')->map(fn($item) => round((float) $item, 2))->values();

        $incomeSummary = CashierLedger::query()
            ->where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::INCOME_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("
                COALESCE(SUM(CASE WHEN method = 'cash' THEN amount END), 0) as total_cash,
                COALESCE(SUM(CASE WHEN method = 'c2c' THEN amount END), 0) as total_c2c,
                COALESCE(SUM(CASE WHEN method = 'pos' THEN amount END), 0) as total_posterminal,
                COALESCE(SUM(amount), 0) as total_all
            ")
            ->first();

        $expenseSummary = CashierLedger::query()
            ->where('cashier_id', $userId)
            ->whereIn('type', CashierLedger::EXPENSE_TYPES)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("
                COALESCE(SUM(amount), 0) as total_all
            ")
            ->first();

        $profitSummary = (float) $incomeSummary->total_all - (float) $expenseSummary->total_all;

        $period = CarbonPeriod::create($from->copy()->startOfDay(), $to->copy()->startOfDay());

        $dailyLabels = [];
        $dailyIncome = [];
        $dailyExpense = [];

        foreach ($period as $date) {
            $day = $date->format('Y-m-d');

            $dailyLabels[] = $date->format('d.m');

            $dailyIncome[] = (float) CashierLedger::query()
                ->where('cashier_id', $userId)
                ->whereIn('type', CashierLedger::INCOME_TYPES)
                ->whereDate('created_at', $day)
                ->sum('amount');

            $dailyExpense[] = (float) CashierLedger::query()
                ->where('cashier_id', $userId)
                ->whereIn('type', CashierLedger::EXPENSE_TYPES)
                ->whereDate('created_at', $day)
                ->sum('amount');
        }



        $allPatientsReceivableTotal = (float) PatientDoctorBalance::query()
            ->where('doctor_id', $userId)
            ->sum('balance');

        $allPartnersDebtTotal = (float) PartnerDoctorBalance::query()
            ->where('doctor_id', $userId)
            ->sum('balance');


        return view('admin.statistics.info', compact(
            'statistics',
            'from',
            'to',
            'lineLabels',
            'lineCounts',
            'lineIncome',
            'incomeSummary',
            'expenseSummary',
            'profitSummary',
            'dailyLabels',
            'dailyIncome',
            'dailyExpense',
            'allPatientsReceivableTotal',
            'allPartnersDebtTotal',
        ));
    }
}
