<?php

namespace Modules\BaseModule\Http\Requests;

use Dornica\Foundation\Core\Enums\IsDeleted;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegenerateUpdatedAtRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'table' => [
                'required',
                'string',
                'max:30',
                'in:books,questions,blogs,subject_contents,teachers,links,courses,course_chapters,instructors'
            ],

            'model' => [
                'required',
                Rule::exists($this->table, 'id')
                    ->where('is_deleted', IsDeleted::NO->value)
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'table' => __('basemodule::field.table'),
            'model' => __('basemodule::field.id')
        ];
    }
}
