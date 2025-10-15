<?php

namespace Modules\CourseStatus\Http\Requests;

use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Rules\CodeRule;
use Modules\BaseModule\Rules\PersianNameWithSpecialCharsRule;
use Modules\CourseStatus\Models\CourseStatus;

class CourseStatusStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "code" => [
                "required",
                'max:32',
                new CodeRule(),
                Rule::unique(CourseStatus::class, 'code')
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
            "is_active" => [
                'nullable',
                new Enum(IsActive::class)
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
            "transfer_status_access" => [
                "nullable",
                "array"
            ],
            "transfer_status_access.*" => [
                "nullable",
                Rule::exists("course_statuses", 'id')
                    ->where('is_active', IsActive::YES->value)
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
            "is_active" => __("basemodule::field.is_active"),
            "is_count" => __("basemodule::field.statuses.is_count"),
            "can_update" => __("basemodule::field.statuses.can_update"),
            "can_delete" => __("basemodule::field.statuses.can_delete"),
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
