<?php

namespace App\Http\Controllers;

use App\Classes\DatePicker;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalIncomeQuery = Transaction::query();
        $totalIncomeQuery = $this->applyFilters($totalIncomeQuery,$request);
        $totalIncome = $totalIncomeQuery->where('type', 'income')->sum('amount');

        $totalExpensesQuery = Transaction::query();
        $totalExpensesQuery = $this->applyFilters($totalExpensesQuery,$request);
        $totalExpenses = $totalExpensesQuery->where('type', 'expense')->sum('amount');

        $capitalDepositQuery = Transaction::query();
        $capitalDepositQuery = $this->applyFilters($capitalDepositQuery,$request);
        $capitalDeposit = $capitalDepositQuery->where('category_id', 4)->sum('amount');

        $capitalWithdrawQuery = Transaction::query();
        $capitalWithdrawQuery = $this->applyFilters($capitalWithdrawQuery,$request);
        $capitalWithdraw = $capitalWithdrawQuery->where('category_id', 6)->sum('amount');

        return [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpenses,
            'capitalDeposit' => $capitalDeposit,
            'capitalWithdraw' => $capitalWithdraw,
        ];
    }

    private function applyFilters($query,$request){
        if ($request['start_date'] != null) {
            $query = $query->whereDate('date', '>=',DatePicker::format($request['start_date']));
        }
        if ($request['end_date'] != null) {
            $query = $query->whereDate('date', '<=',DatePicker::format($request['end_date']));
        }
        if ($request['account_id'] != null) {
            $query = $query->where('account_id', $request['account_id']);
        }
        if ($request['stakeholder_id'] != null) {
            $query = $query->where('stakeholder_id', $request['stakeholder_id']);
        }
        if ($request['project_id'] != null) {
            $query = $query->where('project_id', $request['project_id']);
        }
        return $query;
    }
}
