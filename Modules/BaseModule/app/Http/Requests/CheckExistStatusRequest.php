<?php

namespace Modules\BaseModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\SubjectContentStatus\Enums\UserType;

class CheckExistStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'table' => [
                'required',
                'string',
                'max:50',
                // Target status tables
                'in:teacher_statuses,book_statuses,blog_statuses,course_statuses,subject_content_statuses,page_statuses,ticket_statuses',
            ],
            'field' => [
                'required',
                'string',
                'max:16',
                // Fields that must be uniquely active
                'in:is_start'
            ],
            'current_status_id' => [
                'nullable'
            ],

            'type' => [
                'nullable',
                new Enum(UserType::class)
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'table' => __('basemodule::field.id'),
            'field' => __('basemodule::field.status'),
            'current_status_id' => __('basemodule::field.current_status_id'),
        ];
    }
}
