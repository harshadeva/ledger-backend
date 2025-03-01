<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpenses = Transaction::where('type', 'expense')->sum('amount');
        $capitalDeposit = Transaction::where('category_id', 4)->sum('amount');
        $capitalWithdraw = Transaction::where('category_id', 6)->sum('amount');

        return [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpenses,
            'capitalDeposit' => $capitalDeposit,
            'capitalWithdraw' => $capitalWithdraw,
        ];
    }
}
