<?php

namespace Modules\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\BaseModule\Rules\CheckWorkFlowSetStatusRule;
use Modules\CourseStatus\Models\CourseStatus;

class ChangeCourseStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // You can change this logic to check if the user has the required permission
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'course_status_id' => [
                'required',
                new CheckWorkFlowSetStatusRule('course'),
                Rule::exists(CourseStatus::class, 'id')
                    ->withoutTrashed()
            ],

            'description' => [
                'nullable',
                'string',
                'max:10000'
            ]
        ];
    }

    /**
     * Customize the error messages for the validation rules.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'course_status_id' => __('basemodule::field.status'),
            'description' => __('basemodule::field.description')
        ];
    }
}
