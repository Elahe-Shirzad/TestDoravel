<x-default-layout>
    <x-card
    :title="getPageTitle()"
    >
        <form
        method="POST"
        action="{{route('admin.system-settings.course-settings.course-workflows.store')}}"
        id="create_course_workflow"
        >
            @csrf
            <div class="row g-3">

                <x-select
                    containerClass="col-md-6"
                    label="{{__('basemodule::field.user_role')}}"
                    id="role_id"
                    name="role_id"
                    :items="$roles"
                    :selected="old('role_id')"
                />

                <x-datetime-range-picker
                    container-class="col-md-12"
                    id="date-selector"
                    fromName="start_date"
                    toName="end_date"
                    fromLabel="{{__('basemodule::field.start_date')}}"
                    toLabel="{{__('basemodule::field.end_date')}}"
                    :autoClose="true"
                    :fromValue="old('start_date') ? verta()->parse(old('start_date'))->toCarbon() : null"
                    :toValue="old('end_date') ? verta()->parse(old('end_date'))->toCarbon() : null"
                    :allow-same-day-selection="true"
                />

                <x-multi-select
                    containerClass="col-md-6"
                    id="statuses_to_view"
                    name="statuses_to_view"
                    label="{{__('basemodule::field.statuses_to_view')}}"
                    :items="$courseStatuses"
                    :selected="old('statuses_to_view')"
                    :multiple="true"
                />

                <x-multi-select
                    containerClass="col-md-6"
                    id="statuses_to_change"
                    name="statuses_to_change"
                    label="{{__('basemodule::field.statuses_to_change')}}"
                    :items="$courseStatuses"
                    :selected="old('statuses_to_change')"
                    :multiple="true"
                />

                <x-multi-select
                    containerClass="col-md-6"
                    id="statuses_to_set"
                    name="statuses_to_set"
                    label="{{__('basemodule::field.statuses_to_set')}}"
                    :items="$courseStatuses"
                    :selected="old('statuses_to_set')"
                    :multiple="true"
                />

                <x-text-area
                    name="description"
                    id="description"
                    label="توضیحات"
                    maxlength="1200"
                    :showMaxLength="true"
                />
            </div>

        </form>
        @canAccess('admin.system-settings.course-settings.course-workflows.store')
        <x-slot:footer>
            <div class="pb-0 px-0 d-flex gap-4 justify-content-end">
                <x-reset-button
                    title="{{__('basemodule::operation.reset')}}"
                    type="button"
                    variant="light"
                    appearance="outline"
                    form-id="create_course_workflow"
                />
                <div class="btn-submit-wrapper">
                    <x-button
                        title="{{__('basemodule::operation.submit')}}"
                        type="button"
                        button-type="submit"
                        form-id="create_course_workflow"
                    />
                </div>
            </div>
        </x-slot:footer>
        @endcanAccess
    </x-card>

    @push("scripts")
        @canAccess('admin.system-settings.course-settings.course-workflows.store')
        {!! FormValidator::formRequest(\Modules\CourseWorkflow\Http\Requests\StoreCourseWorkflowRequest::class,"#create_course_workflow") !!}
        @endcanAccess
    @endpush

</x-default-layout>
