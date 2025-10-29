<x-default-layout>
    <x-card
        title="{{__('basemodule::section.seo_settings')}}"
        class="mb-0"
    >
        <form
            id="course-seo"
            enctype="multipart/form-data"
            method="post"
            action="{{ route('admin.courses.seo.update', ['course' => encryptValue($course->id)])}}"
        >
            @csrf
            @method('PATCH')

            <div class="row gy-4">
                <x-text-input
                    :label="__('basemodule::field.seo.title')"
                    name="seo_title"
                    id="seoTitle"
                    max-length="128"
                    message-style="message"
                    containerClass="col-md-6"
                    :value="old('seo_title',$course->seo_title)"
                />

                <x-select
                    :label="__('basemodule::field.seo.robot')"
                    name="seo_robots"
                    id="seoRobots"
                    :Items="$seoRobots"
                    containerClass="col-md-6"
                    message-style="message"
                    :selected="old('seo_robots',$course->seo_robots?->value)"
                />

                <x-slug-input
                    :label="__('basemodule::field.slug')"
                    name="slug"
                    id="slug"
                    max-length="128"
                    class="dir-ltr text-left"
                    parentId="title"
                    :suffix="request()->getSchemeAndHttpHost() . '/courses/'"
                    containerClass="col-md-12"
                    :value="old('slug',$course->slug)"
                />

                @php
                    $seoKeywords = old("seo_keywords") ? implode(",",old("seo_keywords")) : $course->seo_keywords;
                @endphp
                <x-tag-input
                    :label="__('basemodule::field.seo.keywords')"
                    name="seo_keywords"
                    id="seoKeywords"
                    max-length="255"
                    containerClass="col-md-12"
                    message-style="message"
                    :value="$seoKeywords"
                />

                <x-text-area
                    name="seo_description"
                    id="seoDescription"
                    :label="__('basemodule::field.seo.description')"
                    max-length="255"
                    :trim="true"
                    :resizable="false"
                    :showMaxLength="true"
                    :autoSizing="true"
                    :rows="4"
                    containerClass="col-md-12"
                >
                    {!! strip_tags($course->seo_description) !!}
                </x-text-area>
            </div>

        </form>

        @canAccess('admin.courses.seo.update')
        <x-slot:footer>
            <div class="pe-0 d-flex gap-4 justify-content-end">
                <x-reset-button
                    :title="__('basemodule::operation.reset')"
                    size="md"
                    variant="light"
                    appearance="outline"
                    form-id="course-seo"
                />

                <div class="btn-submit-wrapper">
                    <x-button
                        :title="__('basemodule::operation.update.general')"
                        type="button"
                        button-type="submit"
                        form-id="course-seo"
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
        @canAccess('admin.courses.seo.update')
        {!! FormValidator::formRequest(\Modules\Course\Http\Requests\UpdateCourseSeoRequest::class, "#course-seo") !!}
        @endcanAccess
    @endpush
</x-default-layout>
