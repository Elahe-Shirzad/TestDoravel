<?php

namespace Modules\BaseModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\BaseModule\Enums\General\ResourceType;

class SetStatusRequest extends FormRequest
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
            'section' => [
                'required',
                'string',
                Rule::in(ResourceType::values())
            ],

            'status_id' => [
                'required',
                'string',
                'max:99999999'
            ],

            'status_accesses_relation' => [
                'required',
                'string',
                'max:999'
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'section' => __('basemodule::field.section'),
            'status_id' => __('basemodule::field.status'),
            'status_accesses_relation' => __('basemodule::field.transfer_status_access')
        ];
    }
}
