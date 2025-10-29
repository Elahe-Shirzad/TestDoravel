<x-default-layout>
    <x-card
        :title="getPageTitle()"
    >
        <form
            id="update-course"
            action="{{ route("admin.courses.update", encryptValue($course->id))}}"
            enctype="multipart/form-data"
            method="POST"
        >
            @csrf
            @method('PUT')
                    <div class="row g-3">

                        <x-text-input
                            name="title"
                            id="title"
                            :label="__('course::field.course_title')"
                            value="{{old('title',$course->title)}}"
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
                            :selected="old('course_category_id',$course->course_category_id)"
                        />
                        <x-select
                            name="course_level_id"
                            id="course_level_id"
                            :label="__('course::field.course_category_level')"
                            :items="$courseLevels"
                            ContainerClass="col-12 col-md-6"
                            :selected="old('course_level_id',$course->course_level_id)"
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
                            :fromValue="old('started_at') ? verta()->parse(old('started_at'))->toCarbon() : ($course->end_at ? verta()->parseFormat(jdateFormat('datetime_minute'), $course->started_at)->toCarbon() : null) "
                            :toValue="old('end_at') ? verta()->parse(old('end_at'))->toCarbon() : ($course->end_at ? verta()->parseFormat(jdateFormat('datetime_minute'), $course->end_at)->toCarbon() : null)"
                            :allowTyping="true"
                        />
                        <x-select
                            name="instructor_id"
                            id="instructor_id"
                            :label="__('course::field.instructor')"
                            :items="$instructors"
                            ContainerClass="col-12 col-md-6"
                            :selected="old('instructor_id',$course->instructor_id)"
                        />
                        <x-image-picker
                            name="image"
                            id="image"
                            :src="$course->image_id"
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
                        />

                        <x-image-picker
                            name="cover"
                            id="cover"
                            :src="$course->cover_image"
                            :label="__('basemodule::field.course_instruction_image')"
                            :accept="$coverFileType['mimes'] ? convertExtensionsToDotFormat($coverFileType['mimes']) : '.jpg,.png'"
                            :allowed-extensions="$coverFileType['mimes']"
                            :max-size="$coverFileType['maxFileSize']"
                            :allowRename="true"
                            :cropper="true"
                            :allow-recropping="true"
                            :allowRename="true"
                            :clearable="$coverFileType['isRequired'] == 'nullable'"
                            :aspect-ratio="2/2"
                            :file-name-max-length="128"
                        />

                        <x-file-picker
                            name="video"
                            id="video"
                            :value="$course->introduction_video_file_id"
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
                        >{!! old('small_description',$course->small_description) !!}</x-text-area>

                        <x-editor
                            name="description"
                            :label="__('basemodule::field.description')"
                            max-length="10000"
                            mode="simple"
                        > {!!old('description',$course->description)!!}</x-editor>

                    </div>

        </form>
        @canAccess('admin.courses.update')

        <x-slot:footer>
            <div class="pe-0 d-flex gap-4 justify-content-end">
                <x-reset-button
                    title="{{__('basemodule::operation.reset')}}"
                    size="md"
                    variant="light"
                    appearance="outline"
                    form-id="update-course"
                />
                <div class="btn-submit-wrapper">
                    <x-button
                        :title="__('basemodule::operation.update.general')"
                        type="button"
                        button-type="submit"
                        form-id="update-course"
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

    @push("scripts")
        @canAccess('admin.courses.update')
        {!! FormValidator::formRequest(\Modules\Course\Http\Requests\UpdateCourseRequest::class,"#update-course") !!}
        @endcanAccess
    @endpush
</x-default-layout>
