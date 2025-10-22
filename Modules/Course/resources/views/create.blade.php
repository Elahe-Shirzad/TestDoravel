<x-default-layout>
    <x-card
        :title="getPageTitle()"
    >
        <form
            id="create-course-category"
            action="{{ route("admin.courses.store") }}"
            enctype="multipart/form-data"
            method="POST"
        >
            @csrf

        <x-accordion type="single" gap="8px">
            <x-accordion-item
                title="تنظیمات دوره"
                :expanded="true"
            >
                <div class="row g-3">

                    <x-text-input
                    name="title"
                    id="title"
                    :label="__('course::field.course_title')"
                    value="{{old('title')}}"
                    maxlength="256"
                    ContainerClass="col-12 col-md-12"
                    class="persian-name-without-number"
                    />
                    <x-select
                        name="course_category_id"
                        id="course_category_id"
                        :label="__('course::field.course_category')"
                        :items="$courseCategories"
                        ContainerClass="col-12 col-md-6"
                        :selected="old('course_category_id')"
                    />
                    <x-select
                        name="course_level_id"
                        id="course_level_id"
                        :label="__('course::field.course_category_level')"
                        :items="$courseLevels"
                        ContainerClass="col-12 col-md-6"
                        :selected="old('course_level_id')"
                    />

                    <x-datetime-range-picker
                        containerClass="mt-5"
                        id="date-selector-range"
                        fromName="started_at"
                        name="datetime"
                        toName="end_at"
                        type="datetime"
                        :timePickerOptions="['seconds' => false , 'minutes' => true , 'hours' => true]"
                        :fromLabel="__('basemodule::field.start_date')"
                        :toLabel="__('basemodule::field.end_date')"
                        autoClose="true"
                        :fromValue="old('started_at') ? verta()->parse(old('started_at'))->toCarbon() : null"
                        :toValue="old('end_at') ? verta()->parse(old('end_at'))->toCarbon() : null"
                        :allowTyping="true"
                    />
                    <x-select
                        name="instructor_id"
                        id="instructor_id"
                        :label="__('course::field.instructor')"
                        :items="$instructors"
                        ContainerClass="col-12 col-md-6"
                        :selected="old('instructor_id')"
                    />
                    <x-image-picker
                        name="image"
                        id="image"
                        :label="__('basemodule::field.image')"
                        :accept="$imageFileType['mimes'] ? convertExtensionsToDotFormat($imageFileType['mimes']) : '.jpg,.png'"
                        :allowed-extensions="$imageFileType['mimes']"
                        :max-size="$imageFileType['maxFileSize']"
                        :allowRename="true"
                        :cropper="true"
                        :allow-recropping="true"
                        :allowRename="true"
                        :clearable="$imageFileType['isRequired'] == 'nullable'"
                        :aspect-ratio="2/2"
                        :file-name-max-length="128"
{{--                        :src="old('image')"--}}
                    />

                    <x-image-picker
                        name="cover"
                        id="cover"
                        :label="__('basemodule::field.course_instruction_image')"
                        :accept="$coverFileType['mimes'] ? convertExtensionsToDotFormat($coverFileType['mimes']) : '.jpg,.png'"
                        :allowed-extensions="$coverFileType['mimes']"
                        :max-size="$coverFileType['maxFileSize']"
                        :allowRename="true"
                        :cropper="true"
                        :allow-recropping="true"
                        :allowRename="true"
                        :clearable="$coverFileType['isRequired'] == 'nullable'"
{{--                        :required="$imageFileType['isRequired'] == 'required'"--}}
                        :aspect-ratio="2/2"
                        :file-name-max-length="128"
                    />

                    <x-file-picker
                        name="video"
                        id="video"
                        container-class="video"
                        :label="__('basemodule::field.course_instruction_video')"
                        :allowed-extensions="$videoFileType['mimes']"
                        :accept="$videoFileType['mimes'] ? convertExtensionsToDotFormat($videoFileType['mimes']) : '.mp4'"
                        :max-size="$videoFileType['maxFileSize']"
                        :clearable="$videoFileType['isRequired'] == 'nullable'"
                        :showPreviewPopup="true"
                    />
                    <x-text-area
                        name="small_description"
                        id="small_description"
                        :label="__('basemodule::field.small_description')"
                        max-length="10000"
                        :showMaxLength="true"
                    >{!! old('small_description') !!}</x-text-area>

                    <x-editor
                        name="description"
                        :label="__('basemodule::field.description')"
                        max-length="10000"
                        mode="simple"
                    > {!!old('description')!!}</x-editor>

                </div>
            </x-accordion-item>

            <x-accordion-item
                :title="__('basemodule::section.general_settings')"
            >
                <div class="row gy-5">

                    <x-radio-group
                        wrapper-class="flex-wrap"
                        name="comment_status"
                        id="commentState"
                        :label="__('basemodule::field.comment_status')"
                        message-style="message"
                        orientation="horizontal"
                        container-class="col-md-6"
                        :options="convertEnumToArray(Modules\BaseModule\Enums\General\CommentStatus::class, 'basemodule')"
                        :checked="old('comment_status', \Modules\BaseModule\Enums\General\CommentStatus::SEND_COMMENT_WITH_CONFIRM->value)"
                    />

                    <x-radio-group
                        name="user_type"
                        id="userType"
                        :label="__('basemodule::field.user_type')"
                        message-style="message"
                        container-class="col-md-6"
                        orientation="horizontal"
                        :options="convertEnumToArray(\Modules\BaseModule\Enums\General\UserType::class, 'basemodule')"
                        :checked="old('user_type', Modules\BaseModule\Enums\General\UserType::ALL->value)"
                    />

                    <x-checkbox
                        container-class="col-md-6 d-flex align-items-start justify-content-md-center mt-11"
                        id="can_view_comment"
                        name="can_view_comment"
                        :option-label="__('basemodule::field.can_view_comment')"
                        :checked="old('can_view_comment', Modules\BaseModule\Enums\General\BooleanState::NO->value)"
                        :value="old('can_view_comment', Modules\BaseModule\Enums\General\BooleanState::YES->value)"
                    />

                    <x-checkbox
                        container-class="col-md-6 d-flex align-items-start justify-content-md-center mt-11"
                        id="is-special"
                        name="is_special"
                        :option-label="__('basemodule::field.is_special')"
                        :checked="old('is_special', Modules\BaseModule\Enums\General\BooleanState::NO->value)"
                        :value="old('is_special', Modules\BaseModule\Enums\General\BooleanState::YES->value)"
                    />
                </div>
            </x-accordion-item>

            <x-accordion-item
                :title="__('basemodule::section.seo_settings')"
            >
                <div class="row gy-4">
                    <x-text-input
                        :label="__('basemodule::field.seo.title')"
                        name="seo_title"
                        id="seo_title"
                        max-length="128"
                        :trim="true"
                        :value="old('seo_title')"
                        container-class="col-md-6"
                    />

                    <x-select
                        containerClass="col-md-6"
                        name="seo_robots"
                        :label="__('basemodule::field.seo.robot')"
                        :Items="$seoRobots"
                        :selected="old('seo_robots', Modules\BaseModule\Enums\General\SeoRobot::INDEX_FOLLOW->value)"
                    />

                    <x-slug-input
                        :label="__('basemodule::field.slug')"
                        name="slug"
                        parentId="title"
                        :trim="true"
                        :suffix="request()->getSchemeAndHttpHost() . '/courses/'"
                        max-length="128"
                        class="dir-ltr text-left"
                        container-class="col-12"
                        :value="old('slug')"
                    />

                    <x-tag-input
                        :label="__('basemodule::field.seo.keywords')"
                        name="seo_keywords"
                        max-length="255"
                        container-class="col-12"
                        :value="old('seo_keywords')"
                    />

                    <x-text-area
                        :label="__('basemodule::field.seo.description')"
                        name="seo_description"
                        max-length="255"
                        :trim="true"
                        :resizable="false"
                        :showMaxLength="true"
                        :autoSizing="true"
                        :rows="4"
                    >
                        {{old('seo_description')}}
                    </x-text-area>

                </div>
            </x-accordion-item>
        </x-accordion>

        </form>
        @canAccess('admin.courses.store')

        <x-slot:footer>
            <div class="pe-0 d-flex gap-4 justify-content-end">
                <x-reset-button
                    title="{{__('basemodule::operation.reset')}}"
                    size="md"
                    variant="light"
                    appearance="outline"
                    form-id="create-course-category"
                />
                <div class="btn-submit-wrapper">
                    <x-button
                        title="{{__('basemodule::operation.submit')}}"
                        type="button"
                        id="submitBtn"
                        button-type="submit"
                        form-id="create-course-category"
                    />
                </div>
            </div>
        </x-slot:footer>

        @endcanAccess
    </x-card>

    @push("scripts")
        @canAccess('admin.courses.store')
        {!! FormValidator::formRequest(\Modules\Course\Http\Requests\StoreCourseRequest::class, "#create-course-category") !!}
        @endcanAccess

        <script>
            $(function () {
                $('#title').on('keydown keyup change', function () {
                    $('#seo_title').val($(this).val().substring(0, 128));
                });
            });
        </script>
    @endpush
</x-default-layout>
