<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Http\Requests\PeopleStoreRequest;
use App\Http\Resources\Common\PaginationResource;
use App\Http\Resources\Common\SuccessResponse;
use App\Http\Resources\PeopleResource;
use App\Models\Stakeholder;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StakeholderController extends Controller
{
    public function getAll()
    {
        try {
            $records = Stakeholder::all();
            $resource = PeopleResource::collection($records);

            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }

    public function index()
    {
        try {
            $records = Stakeholder::paginate();
            $resource = PeopleResource::collection($records);

            return new SuccessResponse(['data' => $resource,'pagination'=> new PaginationResource($records)]);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }

    public function store(PeopleStoreRequest $request)
    {
        Log::info('store');
        DB::beginTransaction();
        try {
            $record = Stakeholder::create([
                'name' => $request['name'],
                'nick_name' => $request['nick_name'],
                'status' => 1,
            ]);
            DB::commit();
            $resource = new PeopleResource($record);

            return new SuccessResponse(['message' => 'Record saved', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }

    public function update($id, PeopleStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Stakeholder::find($id)->update([
                'name' => $request['name'],
                'nick_name' => $request['nick_name'],
            ]);
            $record = Stakeholder::find($id);
            DB::commit();
            $resource = new PeopleResource($record);

            return new SuccessResponse(['message' => 'Record update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }

    public function show($id)
    {
        try {
            $record = Stakeholder::find($id);
            $resource = new PeopleResource($record);

            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }
}
