<?php

namespace App\Http\Controllers;

use App\Classes\DatePicker;
use App\Exports\TransactionExport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function transactionsDownload(Request $request){
        $totalIncomeQuery = Transaction::query();
        $totalIncomeQuery = $this->applyFilters($totalIncomeQuery,$request);
        $transactions = $totalIncomeQuery->orderBy('date')->get();

        return Excel::download(new TransactionExport($transactions), 'transactions.xlsx');
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
