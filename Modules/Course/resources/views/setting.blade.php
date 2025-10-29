<x-default-layout>
    <x-card
        title="{{__('basemodule::section.seo_settings')}}"
        class="mb-0"
    >
        <form
            id="course-setting"
            enctype="multipart/form-data"
            method="post"
            action="{{ route('admin.courses.settings.update', ['course' => encryptValue($course->id)])}}"
        >
            @csrf
            @method('PATCH')

            <div class="row g-3">

                <x-radio-group
                    wrapper-class="flex-wrap"
                    name="comment_status"
                    id="commentState"
                    :label="__('basemodule::field.comment_status')"
                    message-style="message"
                    orientation="horizontal"
                    container-class="col-md-6"
                    :options="convertEnumToArray(Modules\BaseModule\Enums\General\CommentStatus::class, 'basemodule')"
                    :checked="old('comment_status', $course->comment_status->value)"
                />

                <x-radio-group
                    name="user_type"
                    id="userType"
                    :label="__('basemodule::field.user_type')"
                    message-style="message"
                    container-class="col-md-6"
                    orientation="horizontal"
                    :options="convertEnumToArray(\Modules\BaseModule\Enums\General\UserType::class, 'basemodule')"
                    :checked="old('user_type', $course->user_type->value)"
                />

{{--                <x-checkbox--}}
{{--                    container-class="col-md-6 d-flex align-items-start justify-content-md-center mt-11"--}}
{{--                    id="can_view_comment"--}}
{{--                    name="can_view_comment"--}}
{{--                    :option-label="__('basemodule::field.can_view_comment')"--}}
{{--                    :checked="old('can_view_comment', $course->can_view_comment->value)"--}}
{{--                    :value="old('can_view_comment', \Modules\BaseModule\Enums\General\BooleanState::YES->value)"--}}
{{--                />--}}

{{--                <x-checkbox--}}
{{--                    container-class="col-md-6 d-flex align-items-start justify-content-md-center mt-11"--}}
{{--                    id="is-special"--}}
{{--                    name="is_special"--}}
{{--                    :option-label="__('basemodule::field.is_special')"--}}
{{--                    :checked="old('is_special', $course->is_special->value)"--}}
{{--                    :value="old('is_special', \Modules\BaseModule\Enums\General\BooleanState::YES->value)"--}}
{{--                />--}}




                <x-checkbox
                    :label="__('basemodule::field.special_status')"
                    name="is_special"
                    :label-space-reserved="true"
                    :checked="old('is_special', $course->is_special->value)"
                    :value="old('is_special', \Modules\BaseModule\Enums\General\BooleanState::YES->value)"
                    message-style="message"
                    container-class="col-md-6"
                />

                <x-checkbox
                    :label="__('basemodule::field.can_view_comment')"
                    orientation="horizontal"
                    name="can_view_comment"
                    :checked="old('can_view_comment', $course->can_view_comment->value)"
                    :value="old('can_view_comment')"
                    container-class="col-12 col-md-6"
                />

            </div>
        </form>

        @canAccess('admin.courses.settings.update')
        <x-slot:footer>
            <div class="pe-0 d-flex gap-4 justify-content-end">
                <x-reset-button
                    :title="__('basemodule::operation.reset')"
                    size="md"
                    variant="light"
                    appearance="outline"
                    form-id="course-setting"
                />

                <div class="btn-submit-wrapper">
                    <x-button
                        :title="__('basemodule::operation.update.general')"
                        type="button"
                        button-type="submit"
                        form-id="course-setting"
                    />
                </div>
            </div>
        </x-slot:footer>
        @endcanAccess
    </x-card>


    {{--  Change Status Component  --}}
    <x-change-status
        :route-name="'admin.courses.change-status'"
        section-name-in-workflow="course"
        :modal-badges="[
            ['value' => $course->id, 'variant' => 'info'],
            ['value' => $course->title, 'variant' => 'success']
        ]"
        :model-id="encryptValue($course->id)"
        :status-id="encryptValue($course->course_status_id)"
        parameter-name="course"
        status-accesses-relation="courseStatusAccesses"
        selectBoxName="course_status_id"
        :status-model="\Modules\CourseStatus\Models\CourseStatus::class"
        :form-request="\Modules\Course\Http\Requests\ChangeCourseStatusRequest::class"
    />

    @push('scripts')
        @canAccess('admin.courses.settings.update')
        {!! FormValidator::formRequest(\Modules\Course\Http\Requests\UpdateCourseSettingRequest::class, "#course-setting") !!}
        @endcanAccess
    @endpush
</x-default-layout>
