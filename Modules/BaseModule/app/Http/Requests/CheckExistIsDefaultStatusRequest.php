<?php

namespace Modules\BaseModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\BaseModule\Enums\General\BooleanState;

class CheckExistIsDefaultStatusRequest extends FormRequest
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
                'max:20',
                'in:major_subtypes'
            ],

            'current_is_default' => [
                'nullable',
                new Enum(BooleanState::class)
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
            'current_is_default' => __('basemodule::field.status'),
        ];
    }
}
