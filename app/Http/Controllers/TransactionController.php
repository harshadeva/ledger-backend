<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Classes\DatePicker;
use App\Enums\HttpStatus;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\Common\PaginationResource;
use App\Http\Resources\Common\SuccessResponse;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pageSize = $request['pageSize'] ?? 20;
            $query = Transaction::query();
            if ($request['start_date'] != null) {
                $query = $query->whereDate('date', '>=',DatePicker::format($request['start_date']));
            }
            if ($request['end_date'] != null) {
                $query = $query->whereDate('date', '<=',DatePicker::format($request['end_date']));
            }
            if ($request['ref'] != null) {
                $query = $query->where('ref', 'like', '%' . $request['ref'] . '%');
            }
            if ($request['description'] != null) {
                $query = $query->where('description', 'like', '%' . $request['description'] . '%');
            }
            if ($request['type'] != null) {
                $query = $query->where('type', $request['type']);
            }
            if ($request['account_id'] != null) {
                $query = $query->where('account_id', $request['account_id']);
            }
            if ($request['stakeholder_id'] != null) {
                $query = $query->where('stakeholder_id', $request['stakeholder_id']);
            }
            $transactions = $query->latest()->paginate($pageSize);
            $resource = TransactionResource::collection($transactions);

            return new SuccessResponse(['data' => $resource,'pagination'=> new PaginationResource($transactions)]);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }

    public function store(TransactionRequest $request)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'account_id' => $request['account_id'],
                'stakeholder_id' => $request['stakeholder_id'],
                'category_id' => $request['category_id'],
                'project_id' => $request['project_id'],
                'amount' => $request['amount'],
                'date' => DatePicker::format($request['date']),
                'type' => strtoupper($request['type']),
                'ref' => $request['reference'],
                'description' => $request['description'],
                'status' => 1,
            ]);
            DB::commit();
            $resource = new TransactionResource($transaction);

            return new SuccessResponse(['message' => 'Transaction saved', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }

    public function update($id, TransactionRequest $request)
    {
        DB::beginTransaction();
        try {
            Transaction::find($id)->update([
                'account_id' => $request['account_id'],
                'stakeholder_id' => $request['stakeholder_id'],
                'category_id' => $request['category_id'],
                'project_id' => $request['project_id'],
                'amount' => $request['amount'],
                'date' => DatePicker::format($request['date']),
                'type' => $request['type'],
                'ref' => $request['reference'],
                'description' => $request['description'],
            ]);
            $transaction = Transaction::find($id);
            DB::commit();
            $resource = new TransactionResource($transaction);

            return new SuccessResponse(['message' => 'Transaction update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }

    public function show($id)
    {
        try {
            $transaction = Transaction::find($id);
            $resource = new TransactionDetailResource($transaction);

            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }


    public function destroy($id)
    {
        try {
            $record = Transaction::find($id);
            if ($record == null) {
                throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Record not found');
            }
            $record->delete();

            return new SuccessResponse(['message' => 'Record deleted']);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }
}
