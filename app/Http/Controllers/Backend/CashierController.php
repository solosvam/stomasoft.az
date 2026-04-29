<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CashierExpenseRequest;
use App\Http\Requests\Backend\CashierIncomeRequest;
use App\Services\Cashier\CashierExpenseService;
use App\Services\Cashier\CashierIncomeService;
use Illuminate\Http\Request;
use App\Services\Cashier\CashierIndexService;
use Illuminate\Http\RedirectResponse;

class CashierController extends Controller
{
    public function index(Request $request, CashierIndexService $cashierIndexService)
    {
        $data = $cashierIndexService->getData(auth()->id(), $request->dates);

        return view('admin.cashier.index', $data);
    }

    public function expence(CashierExpenseRequest $request, CashierExpenseService $cashierExpenseService)
    {
        try {
            $cashierExpenseService->handle(
                auth()->id(),
                (float) $request->amount,
                $request->description
            );

            return back()->with('success', 'Xərc yazıldı');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function income(CashierIncomeRequest $request, CashierIncomeService $cashierIncomeService): RedirectResponse
    {
        $cashierIncomeService->handle(
            auth()->id(),
            (float) $request->amount,
            $request->description
        );

        return back()->with('success', 'Mədaxil olundu');
    }
}
