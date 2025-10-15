<?php

namespace Modules\BaseModule\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\BaseModule\Enums\General\InteractionType;
use Modules\BaseModule\Enums\General\IsRead;
use Modules\BaseModule\Enums\General\ResourceType;

class InteractionRequest extends FormRequest
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
            'id' => [
                'required',
                'string',
                'max:99999999'
            ],

            'resource_type' => [
                'required',
                'string',
                Rule::in(ResourceType::values())
            ],

            'interaction_type' => [
                'required',
                'string',
                Rule::in(InteractionType::values())
            ],

            'relations_with' => [
                'nullable',
                'array'
            ],

            'relations_with.*' => [
                'nullable',
                'in:educationalGroup,educationalGrade,teacher,ticketDepartment,ticketCategory'
            ],

            'is_read' => [
                'nullable',
                Rule::in([IsRead::YES->value])
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id' => __('basemodule::field.id'),
            'resource_type' => __('basemodule::field.type'),
            'interaction_type' => __('basemodule::field.type'),
            'relations_with' => __('basemodule::field.relation'),
            'relations_with.*' => __('basemodule::field.relation')
        ];
    }
}
