<?php

namespace Modules\Bank\Http\Requests;

use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Bank\Enums\BankType;
use Modules\Bank\Enums\Files\FileType;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $avatarFileTypeInfo = getFileType(FileType::BANK, 'bank_image');
        $avatarFileType = getUploadRequirements($avatarFileTypeInfo);
        $avatarRules = makeFileValidationRules('image', $avatarFileType);
         $rules =[
            'name' => [
                'required',
                'string',
                'min:2',
                'max:64',
            ],

            'code' => [
                'required',
                'string',
                'max:16',
            ],

            "color" => [
                "nullable",
                "string",
                'max:16'
            ],
            "is_active" => [
                'nullable',
                new Enum(IsActive::class)
            ],
            "type" => [
                'nullable',
                new Enum(BankType::class)
            ],
            "description" => [
                "nullable",
                "string",
                "max:1025"
            ],
            "location_id" => [
                "required",
                "array"
            ],
            "location_id.*" => [
                "required",
                'exists:Modules\Bank\Models\Location,id'
            ],
            "published_at" => [
                "nullable",
            ],
            "expired_at" => [
                "nullable",
            ],
        ];

        return array_merge($rules, $avatarRules);
    }

    public function attributes(): array
    {
        return [
            'code' => __('bank::general.code'),
            'published_at' => __('bank::general.published_at'),
            'expired_at' => __('bank::general.expired_at'),
            'description' => __('bank::general.description'),
            'color' => __('bank::general.color'),
            'is_active' => __('bank::general.is_active'),
            'location_id' => __('bank::general.location_id'),
            'image' => __('bank::general.bank_image'),

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
