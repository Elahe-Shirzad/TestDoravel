<?php

namespace Modules\CourseWorkflow\Http\Requests;

use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\CourseWorkflow\Models\CourseWorkflow;

class StoreCourseWorkflowRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')
                    ->where('is_active', IsActive::YES)
                    ->withoutTrashed()
            ],
            'start_date' => [
                'required',
                'jdate',
                $this->end_date ? 'jdate_before_equal:' . $this->end_date . ',' . jdateFormat(type: 'date') : null
            ],
            'end_date' => [
                'required',
                'jdate',
                $this->start_date ? 'jdate_after_equal:' . $this->start_date . ',' . jdateFormat(type: 'date') : null
            ],
            'description' => [
                'nullable',
                'string',
                "max:10000"
            ],
            'statuses_to_view' => [
                'required',
                'array'
            ],
            'statuses_to_view.*' => [
                'required',
                Rule::exists('course_statuses', 'id')
                    ->where('is_active', IsActive::YES->value)
                    ->withoutTrashed()
            ],
            'statuses_to_change' => [
                'nullable',
                'array'
            ],
            'statuses_to_change.*' => [
                'nullable',
                Rule::exists('course_statuses', 'id')
                    ->where('is_active', IsActive::YES->value)
                    ->withoutTrashed()
            ],
            'statuses_to_set' => [
                'nullable',
                'array'
            ],
            'statuses_to_set.*' => [
                'nullable',
                Rule::exists('course_statuses', 'id')
                    ->where('is_active', IsActive::YES->value)
                    ->withoutTrashed()
            ],
        ];
    }

    /**
     * @return void
     */
    protected function passedValidation(): void
    {
        if (checkDatesOverlap(CourseWorkflow::class, $this->start_date, $this->end_date, $this->role_id)) {
            throw new HttpResponseException(redirect(route('admin.system-settings.course-settings.course-workflows.create'))->
            withFlash(message: __('basemodule::message.date_overlap_error'),type: 'error')->
            withInput());
        }
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'role_id' => __('basemodule::field.role_id'),
            'start_date' => __('basemodule::field.start_date'),
            'end_date' => __('basemodule::field.end_date'),
            'description' => __('basemodule::field.description'),
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
