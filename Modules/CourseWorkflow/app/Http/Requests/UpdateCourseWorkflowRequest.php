<?php

namespace Modules\CourseWorkflow\Http\Requests;

use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\CourseWorkflow\Models\CourseWorkflow;

class UpdateCourseWorkflowRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "role_id" => [
                "required",
                Rule::exists("roles", 'id')
            ],
            "start_date" => [
                "required",
                "jdate",
                $this->end_date ? 'jdate_before_equal:' . $this->end_date . ',' . jdateFormat(type: 'date') : null
            ],
            "end_date" => [
                "required",
                "jdate",
                $this->start_date ? 'jdate_after_equal:' . $this->start_date . ',' . jdateFormat(type: 'date') : null
            ],
            "description" => [
                "nullable",
                "string",
                "max:10000"
            ],
            "is_active" => [
                'nullable',
                new Enum(IsActive::class)
            ],
            'statuses_to_view' => [
                'required',
                'array'
            ],
            'statuses_to_view.*' => [
                'required',
                Rule::exists('course_statuses', 'id')->
                withoutTrashed()
            ],
            'statuses_to_change' => [
                'nullable',
                'array'
            ],
            'statuses_to_change.*' => [
                'nullable',
                Rule::exists('course_statuses', 'id')->
                withoutTrashed()
            ],
            'statuses_to_set' => [
                'nullable',
                'array'
            ],
            'statuses_to_set.*' => [
                'nullable',
                Rule::exists('course_statuses', 'id')->
                withoutTrashed()
            ],
        ];
    }

    /**
     * @return void
     */
    protected function passedValidation(): void
    {
        if (checkDatesOverlap(CourseWorkflow::class, $this->start_date, $this->end_date, $this->role_id, $this->getCurrentId())) {
            throw new HttpResponseException(redirect(route('admin.system-settings.course-settings.course-workflows.edit',
                encryptValue($this->getCurrentId())))->
            withFlash(message: __('basemodule::message.date_overlap_error'), type: 'error')->
            withInput());
        }
    }

    protected function getCurrentId()
    {
        return Route::getCurrentRoute()->parameter('course_workflow')->id;
    }

    public function attributes(): array
    {
        return [
            'role_id' => __('basemodule::field.user_role'),
            'start_date' => __('basemodule::field.start_date'),
            'end_date' => __('basemodule::field.end_date'),
            'description' => __('basemodule::field.description'),
            'is_active' => __('basemodule::field.status'),
            'statuses_to_view' => __('basemodule::field.statuses_to_view'),
            'statuses_to_view.*' => __('basemodule::field.statuses_to_view'),
            'statuses_to_change' => __('basemodule::field.statuses_to_change'),
            'statuses_to_change.*' => __('basemodule::field.statuses_to_change'),
            'statuses_to_set' => __('basemodule::field.statuses_to_set'),
            'statuses_to_set.*' => __('basemodule::field.statuses_to_set'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
