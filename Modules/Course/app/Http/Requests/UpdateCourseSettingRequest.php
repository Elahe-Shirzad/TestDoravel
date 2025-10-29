<?php

namespace Modules\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Enums\General\CommentStatus;
use Modules\BaseModule\Enums\General\UserType;

class UpdateCourseSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'can_view_comment' => [
                'nullable',
                'boolean'
            ],
            'comment_status' => [
                'nullable',
                new Enum(CommentStatus::class)
            ],
            'user_type' => [
                'nullable',
                new Enum(UserType::class)
            ],
            'is_special' => [
                'nullable',
                'boolean:'
            ],
        ];
    }

    public function attributes(): array
    {
        return [

            'comment_status' => __('basemodule::field.comment_status'),
            'user_type' => __('basemodule::field.user_type'),
            'is_special' => __('basemodule::field.is_special'),
            'can_view_comment' => __('basemodule::field.can_view_comment'),

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
