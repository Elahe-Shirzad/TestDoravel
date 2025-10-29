<?php

namespace Modules\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\BaseModule\Enums\General\SeoRobot;
use Modules\Course\Models\Course;

class UpdateCourseSeoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $course = Route::getCurrentRoute()->parameter('course');

        return [
            'slug' => [
                'required',
                'string',
                'max:128',
                Rule::unique(Course::class, 'slug')
                    ->ignore($course->id)
                    ->withoutTrashed(),
            ],
            'seo_title' => [
                'nullable',
                'string',
                'max:128'
            ],
            'seo_description' => [
                'nullable',
                'string',
                'max:255'
            ],
            'seo_robots' => [
                'nullable',
                'force_remote',
                new Enum(SeoRobot::class)
            ],
            'seo_keywords' => [
                'nullable',
                'string',
                'max:255'
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
            'seo_title' => __('basemodule::field.seo.title'),
            'seo_description' => __('basemodule::field.seo.description'),
            'seo_keywords' => __('basemodule::field.seo.keywords'),
            'seo_robots' => __('basemodule::field.seo.robot'),
            'slug' => __('basemodule::field.slug'),
        ];
    }
    public function authorize(): bool
    {
        return true;
    }
}
