<?php

namespace Modules\BaseModule\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\Foundation\Core\Enums\IsDeleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Modules\Bank\Enums\BooleanState;
use Modules\BaseModule\Enums\General\InteractionType;
use Modules\BaseModule\Helpers\InteractionResolver;
use Modules\BaseModule\Http\Requests\CheckExistIsDefaultStatusRequest;
use Modules\BaseModule\Http\Requests\CheckExistStatusRequest;
use Modules\BaseModule\Http\Requests\SetStatusRequest;

class StatusController extends Controller
{
    // Get statuses for select box in change status modal
    public function setStatuesForSelectBox(SetStatusRequest $request)
    {
        $workflowStatuses = getUserCurrentRoleWorkflow($request->section);
        $setStatuses = $workflowStatuses->get('set', collect());

        $statusModel = InteractionResolver::model(
            interactionType: InteractionType::JUST_MODEL->value,
            resourceType: $request->section . "_status"
        );

        $status = app($statusModel)::find($request->status_id);

        $accessRelation = $request->status_accesses_relation;
        $accessIds = [];

        if ($status && method_exists($status, $accessRelation)) {
            $relationColumn = "child_{$request->section}_status_id";
            $accessIds = $status->{$accessRelation}()->pluck($relationColumn)->toArray();
        }

        $statusForSet = $setStatuses->intersect($accessIds);

        $statuses = app($statusModel)::whereIn('id', $statusForSet->all())->where('is_active', IsActive::YES)->get();
        $viewStatuses = prepareSelectComponentData($statuses);

        return Response::success(data: [
            'viewStatuses' => $viewStatuses,
            'statusInfo' => encryptIdentifiers($status->toArray())
        ]);
    }

    // Check if any is default status for the given field already exists
    public function checkIfIsDefaultStatusExist(CheckExistIsDefaultStatusRequest $request): \Illuminate\Http\Response
    {
        $data = DB::table($request->table)
            ->where('is_deleted', IsDeleted::NO->value)
            ->where('is_default', BooleanState::YES)
            ->when($request->filled('filter_column') && $request->filled('filter_value'), function ($query) use ($request) {
                $query->where($request->filter_column, $request->filter_value);
            })
            ->when($request->filled('current_default_id'), function ($query) use ($request) {
                $query->where('id', '<>', $request->current_default_id);
            })
            ->first();

        return Response::success(
            data: [
                'statusExists' => isset($data),
                'statusTitle' => isset($data) ? $data->name : '',
            ]
        );
    }


    // Check if any active status for the given field already exists
    public function checkIfActiveStatusExist(CheckExistStatusRequest $request): \Illuminate\Http\Response
    {
        $activeValue = BooleanState::YES->value;
        $status = DB::table($request->table)
            ->where($request->field, $activeValue)
            ->where('is_deleted', IsDeleted::NO->value)
            // Excludes the current status in edit, when checking if any other active statuses exist
            ->when($request->has('current_status_id'), function ($query) use ($request) {
                $query->where('id', '<>', $request->current_status_id);
            })->first();

        return Response::success(
            data: [
                'statusExists' => isset($status),
                'statusTitle' => isset($status) ? $status->name : '',
                //'is_lock' => isset($status) ? $status->is_lock : false
            ]
        );
    }
}
