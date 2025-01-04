<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Classes\DatePicker;
use App\Enums\HttpStatus;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Resources\Common\SuccessResponse;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            $query = Project::query();
            if ($request->has('name')) {
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            if ($request->has('start_date')) {
                $query->where('start_date', '>=', DatePicker::format($request->start_date));
            }
            if ($request->has('due_date')) {
                $query->where('due_date', '<=', DatePicker::format($request->due_date));
            }
            $records = $query->get();
            $resource = ProjectResource::collection($records);

            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }

    public function index(Request $request)
    {
        try {
            Log::info($request->all());
            $query = Project::query();
            if ($request->has('name')) {
                $query->where('name', 'like', '%'.$request->name.'%');
            }
            if ($request->has('start_date')) {
                $query->where('start_date', '>=', DatePicker::format($request->start_date));
            }
            if ($request->has('due_date')) {
                $query->where('due_date', '<=', DatePicker::format($request->due_date));
            }
            $records = $query->paginate();
            $resource = ProjectResource::collection($records);

            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }

    public function store(ProjectStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $record = Project::create([
                'name' => $request['name'],
                'total' => $request['total'],
                'start_date' => DatePicker::format($request['start_date']),
                'due_date' => DatePicker::format($request['due_date']),
                'status' => 1,
            ]);
            DB::commit();
            $resource = new ProjectResource($record);

            return new SuccessResponse(['message' => 'Record saved', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }

    public function update($id, ProjectStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Project::find($id)->update([
                'name' => $request['name'],
                'total' => $request['total'],
                'start_date' => DatePicker::format($request['start_date']),
                'due_date' => DatePicker::format($request['due_date']),
                'status' => 1,
            ]);
            $record = Project::find($id);
            DB::commit();
            $resource = new ProjectResource($record);

            return new SuccessResponse(['message' => 'Record update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }

    public function show($id)
    {
        try {
            $record = Project::find($id);
            $resource = new ProjectResource($record);

            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $record = Project::find($id);
            if ($record == null) {
                throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Record not found');
            }
            if ($record->transactions()->exists()) {
                throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Can not delete project');
            }
            $record->delete();

            return new SuccessResponse(['message' => 'Project deleted']);
        } catch (Exception $e) {
            ApiCatchErrors::throwException($e);
        }
    }
}
