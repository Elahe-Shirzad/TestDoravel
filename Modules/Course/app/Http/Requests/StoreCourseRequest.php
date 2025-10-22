<?php

namespace Modules\Course\Http\Requests;

use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Bank\Enums\Files\FileType;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Enums\General\CommentStatus;
use Modules\BaseModule\Enums\General\SeoRobot;
use Modules\BaseModule\Enums\General\UserType;
use Modules\BaseModule\Rules\PersianNameWithSpecialCharsRule;
use Modules\CourseCategory\Models\CourseCategory;
use Modules\CourseLevel\Models\CourseLevel;
use Modules\CourseStatus\Models\CourseStatus;
use Modules\Instructor\Models\Instructor;

class StoreCourseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {



        $imageFileTypeInfo = getFileType(FileType::COURSEIMAGE, 'course_image');
        $imageFileType = getUploadRequirements($imageFileTypeInfo);
        $imageRules = makeFileValidationRules('image', $imageFileType);

        $videoFileTypeInfo = getFileType(FileType::COURSEINTRODUCTIONVIDEO, 'course_introduction_video');
        $videoFileType = getUploadRequirements($videoFileTypeInfo);
        $videoRules = makeFileValidationRules('video', $videoFileType);

        $coverFileTypeInfo = getFileType(FileType::COURSECOVERIMAGE, 'course_cover_image');
        $coverFileType = getUploadRequirements($coverFileTypeInfo);
        $caverRules = makeFileValidationRules('cover', $coverFileType);

        $rules=[
            'title'=>[
                'required',
                'string',
                'min:2',
                'max:256',
                new PersianNameWithSpecialCharsRule()
            ],
            'course_level_id'=>[
                'required',
                Rule::exists(CourseLevel::class, 'id')
                    ->where('is_active', IsActive::YES)
                    ->withoutTrashed(),
            ],
            'course_category_id'=>[
                'required',
                Rule::exists(CourseCategory::class, 'id')
                    ->where('is_active', IsActive::YES)
                    ->withoutTrashed(),
            ],
            'instructor_id'=>[
                'required',
                Rule::exists(Instructor::class, 'id')
                    ->where('is_active', IsActive::YES)
                    ->withoutTrashed(),
            ],
            'started_at' => [
                'required',
                'force_remote',
                $this->end_at ? 'jdatetime_before_equal:' . $this->end_at . ',' . jdateFormat('datetime_minute') : null
            ],
            'end_at' => [
                'required',
                'force_remote',
                $this->started_at ? 'jdatetime_after_equal:' . $this->started_at . ',' . jdateFormat('datetime_minute') : null
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
            ],
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
                new Enum(BooleanState::class)
            ],
            'small_description'=>[
                'required',
                'string',
                'min:2',
                'max:512',
            ],
            'description'=>[
                'nullable',
                'string',
                'min:2',
                'max:1000',
            ],
        ];

        return array_merge($rules, $imageRules, $videoRules, $caverRules);
    }

    public function attributes(): array
    {
        return [
            'course_category_id' => __('course::field.course_category'),
            'course_level_id' => __('course::field.course_category_level'),
            'instructor_id' => __('course::field.instructor'),
            'image' => __('basemodule::field.image'),
            'cover' => __('basemodule::field.cover_image'),
            'video' => __('basemodule::field.course_instruction_video'),
            'header' => __('basemodule::field.header'),
            'title' => __('basemodule::field.title'),
            'started_at' => __('basemodule::field.start_date'),
            'end_at' => __('basemodule::field.end_date'),
            'comment_status' => __('basemodule::field.comment_status'),
            'sub_title' => __('blog::general.fields.sub_title'),
            'seo_title' => __('basemodule::field.seo.title'),
            'seo_description' => __('basemodule::field.seo.description'),
            'seo_keywords' => __('basemodule::field.seo.keywords'),
            'seo_robots' => __('basemodule::field.seo.robot'),
            'description' => __('basemodule::field.description'),
            'small_description' => __('basemodule::field.small_description'),
            'slug' => __('basemodule::field.slug'),
            'style' => __('basemodule::field.style'),
            'user_type' => __('basemodule::field.user_type'),
            'read_time' => __('blog::general.fields.read_time'),
            'is_special' => __('basemodule::field.is_special'),
            'can_view_comment' => __('basemodule::field.can_view_comment'),
            'published_at' => __('basemodule::field.published_at'),
            'expired_at' => __('basemodule::field.expired_at'),
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
