<?php

namespace Modules\Location\Http\Requests;

use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Enum;
use Modules\Bank\Enums\Files\FileType;
use Modules\Bank\Models\Bank;
use Modules\Bank\Models\Location;
use Modules\Location\Enums\Service;

class LocationUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $location = Route::getCurrentRoute()->parameter('location');
        $avatarFileType = getFileType(FileType::LOCATION, 'location_avatar');
        $avatarFileTypeInfo = getUploadRequirements(
            documentType: $avatarFileType,
            entity: Location::class,
            entityId: $location->id,
            entityFileRelation: 'avatar'
        );
        $avatarRules = makeFileValidationRules('avatar', $avatarFileTypeInfo);

        $rules = [
            'branch' => [
                'required',
                'string',
                'max:64',
            ],
            "sort" => [
                'required',
                'integer',
                'min:1',
                'max:9999999999999999',
            ],

            'square' => [
                'required',
                'string',
                'max:64',
            ],
            'street' => [
                'nullable',
                'string',
                'max:64',
            ],
            'alley' => [
                'nullable',
                'string',
                'max:64',
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
            "service" => [
                'required',
                new Enum(Service::class)
            ],
            "description" => [
                "nullable",
                "string",
                "max:1025"
            ],
            "published_at" => [
                "nullable",
            ],
            "expired_at" => [
                "nullable",
            ],
            'full_address'=>[
                'nullable',
                'string',
                "max:256"
            ]
        ];

        return array_merge($rules, $avatarRules);
    }

    public function attributes(): array
    {
        return [
            'branch' => __('location::general.branch'),
            'square' => __('location::general.square'),
            'street' => __('location::general.street'),
            'alley' => __('location::general.alley'),
            'published_at' => __('location::general.published_at'),
            'expired_at' => __('location::general.expired_at'),
            'description' => __('location::general.description'),
            'color' => __('location::general.color'),
            'is_active' => __('location::general.is_active'),
            'location_id' => __('location::general.location_id'),
            'avatar' => __('location::general.location_image'),
            'full_address' => __('location::general.full_address'),

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
