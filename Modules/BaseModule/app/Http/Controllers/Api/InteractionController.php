<?php

namespace Modules\BaseModule\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Support\Facades\Response;
use Modules\BaseModule\Enums\General\InteractionType;
use Modules\BaseModule\Enums\General\IsRead;
use Modules\BaseModule\Enums\General\ResourceType;
use Modules\BaseModule\Helpers\InteractionResolver;
use Modules\BaseModule\Http\Requests\InteractionRequest;

class InteractionController extends Controller
{
    /**
     * @param InteractionRequest $request
     * @return mixed
     */
    public function changeStatus(InteractionRequest $request)
    {
        $resourceType = $request->input('resource_type');
        $interactionType = $request->input('interaction_type');

        $modelClass = InteractionResolver::model(
            interactionType: $interactionType,
            resourceType: $resourceType
        );

        if (!$modelClass) {
            return Response::dataNotFound();
        }
        $record = app($modelClass)::find($request->input('id'));

        if (!$record) {
            return Response::dataNotFound();
        }

        $data = [];
        //$data['is_active'] = IsActive::YES;

        // Toggle is_active status
        if ($record->is_active === IsActive::YES) {
            $data['is_active'] = IsActive::NO;
        } else {
            $data['is_active'] = IsActive::YES;
        }

        // Handle comment-specific logic
        if (
            $interactionType === InteractionType::COMMENT->value &&
            $request->is_read == IsRead::YES->value &&
            $record->is_read !== IsRead::YES
        ) {
            $data = [
                'is_read' => IsRead::YES,
                'read_at' => now()
            ];
        }

        if ($data) {
            $record->update($data);

            return Response::update(
                message: __('basemodule::message.update_successfully')
            );
        }

        return Response::update(
            message: __('basemodule::message.function_without_result')
        );
    }

    /**
     * @param InteractionRequest $request
     * @return mixed
     */
    public function show(InteractionRequest $request)
    {
        $resourceType = $request->input('resource_type');
        $interactionType = $request->input('interaction_type');
        $recordId = $request->input('id');

        $modelClass = InteractionResolver::model(
            interactionType: $interactionType,
            resourceType: $resourceType
        );

        // ToDo: Define ResourceTypes and InteractionTypes generally

        // Handle blog-specific interactions
        if (($resourceType == ResourceType::BLOG->value) &&
            ($interactionType != InteractionType::JUST_MODEL->value)) {
            $resourceClass = InteractionResolver::resource($interactionType);
        }
        else if (($resourceType == ResourceType::QUESTION->value) &&
            ($interactionType != InteractionType::JUST_MODEL->value) && ($interactionType != InteractionType::FAVORITE->value)) {
            $resourceClass = InteractionResolver::resource($interactionType);
        }
        else if (($resourceType == ResourceType::SUBJECT_CONTENT->value) &&
            ($interactionType != InteractionType::JUST_MODEL->value) && ($interactionType != InteractionType::FAVORITE->value)) {
            $resourceClass = InteractionResolver::resource($interactionType);
        }
        else if (($resourceType == ResourceType::COURSE->value) &&
            ($interactionType != InteractionType::JUST_MODEL->value)) {
            $resourceClass = InteractionResolver::resource($interactionType);
        } else {
            $resourceClass = InteractionResolver::resource(resourceType: $resourceType);
        }

        if (!$modelClass || (!$resourceClass && $interactionType != InteractionType::JUST_MODEL->value)) {
            return Response::dataNotFound();
        }

        // $modelInstance = app($modelClass);
        // $with = in_array('member_id', $modelInstance->getFillable()) ? ['member'] : [];
        // convert each relation to camel case format
        $with = $request->input('relations_with') ?? [];
        $record = $modelClass::with($with)->find($recordId);

        if (!$record) {
            return Response::dataNotFound();
        }

        $data = new $resourceClass($record);

        return Response::success(data: $data);
    }

    /**
     * @param InteractionRequest $request
     * @return mixed
     */
    public function destroy(InteractionRequest $request)
    {
        $resourceType = $request->input('resource_type');
        $interactionType = $request->input('interaction_type');

        $modelClass = InteractionResolver::model(
            interactionType: $interactionType,
            resourceType: $resourceType
        );

        if (!$modelClass) {
            Response::dataNotFound();
        }

        $record = app($modelClass)::find($request->input('id'));

        if (!$record) {
            return Response::dataNotFound();
        }

        $record->delete();

        return Response::destroy(
            message: __('basemodule::message.delete_successfully')
        );
    }
}
