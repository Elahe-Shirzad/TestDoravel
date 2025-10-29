<?php

namespace Modules\Course\Http\Requests;

use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SearchCourseChapterRequest extends FormRequest
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
            'title' => [
                'nullable',
                'string',
                'max:128',
            ],
            'is_active' => [
                'nullable',
                new Enum(IsActive::class)
            ],
        ];
    }

    /**
     * Customize the error messages for the validation rules.
     *
     * @return array
     */
}
