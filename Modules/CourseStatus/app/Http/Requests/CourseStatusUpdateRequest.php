<?php

namespace Modules\CourseStatus\Http\Requests;

use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Rules\CodeRule;
use Modules\BaseModule\Rules\NumberSeparatorRule;
use Modules\BaseModule\Rules\PersianNameWithSpecialCharsRule;
use Modules\CourseStatus\Models\CourseStatus;

class CourseStatusUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = Route::getCurrentRoute()->parameter('status')->id;
        // ToDo: Check retrieving from db (performance-wise)
        $isLocked = CourseStatus::where('id', $id)->where('is_lock', BooleanState::YES->value)->exists();

        return [
            "code" => [
                $isLocked ? "null" : "required",
                'max:32',
                new CodeRule(),
                Rule::unique(CourseStatus::class, 'code')
                    ->ignore($id)
                    ->withoutTrashed()
            ],
            "name" => [
                "required",
                "string",
                "min:2",
                'max:128',
                new PersianNameWithSpecialCharsRule()
            ],
            "color" => [
                "nullable",
                "string",
                'max:16'
            ],
            "is_start" => [
                'nullable',
                new Enum(BooleanState::class)
            ],
            "is_end" => [
                'nullable',
                new Enum(BooleanState::class)
            ],
            "is_count" => [
                'nullable',
                new Enum(BooleanState::class)
            ],
            "is_publish" => [
                'nullable',
                new Enum(BooleanState::class)
            ],
            "can_update" => [
                'nullable',
                new Enum(BooleanState::class)
            ],
            "can_delete" => [
                'nullable',
                new Enum(BooleanState::class)
            ],
            "description" => [
                "nullable",
                "string",
                "max:32000"
            ],
            "sort" => [
                'required',
                'integer',
                'min:1',
                new NumberSeparatorRule(99999999)
            ],
            "is_active" => [
                'nullable',
                new Enum(IsActive::class)
            ],
            "transfer_status_access" => [
                "nullable",
                "array"
            ],
            "transfer_status_access.*" => [
                "nullable",
                Rule::exists("course_statuses", 'id')
                    ->withoutTrashed()
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            "code" => __('basemodule::field.code'),
            "name" => __('basemodule::field.name'),
            "color" => __('basemodule::field.color'),
            "is_start" => __("basemodule::field.statuses.is_start"),
            "is_end" => __("basemodule::field.statuses.is_end"),
            "is_count" => __("basemodule::field.statuses.is_count"),
            "can_update" => __("basemodule::field.statuses.can_update"),
            "can_delete" => __("basemodule::field.statuses.can_delete"),
            "sort" => __("basemodule::field.sort"),
            "is_active" => __("basemodule::field.status"),
            "description" => __("basemodule::field.description"),
            "transfer_status_access" => __("basemodule::field.transfer_status_access"),
            "transfer_status_access.*" => __("basemodule::field.transfer_status_access"),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
