<x-default-layout>
    <x-card
        class="mt-5 mt-md-0 mb-5"
    >
        <form
            id="edit_course_workflow"
            action="{{ route('admin.system-settings.course-settings.course-workflows.update',encryptValue($courseWorkflow->id)) }}"
            method="post"
        >
            @csrf
            @method("put")

            <div class="row gy-4">
                <x-select
                    containerClass="col-md-6"
                    label="{{__('basemodule::field.user_role')}}"
                    id="role_id"
                    name="role_id"
                    :items="$roles"
                    :selected="$courseWorkflow->role_id"
                />

                <div class="col-6 m-0"></div>

                <x-datetime-range-picker
                    container-class="col-md-12"
                    id="date-selector"
                    fromName="start_date"
                    toName="end_date"
                    fromLabel="{{__('basemodule::field.start_date')}}"
                    toLabel="{{__('basemodule::field.end_date')}}"
                    :autoClose="true"
                    :fromValue="old('start_date') ? verta()->parse(old('start_date'))->toCarbon() : verta()->parse($courseWorkflow->start_date)->toCarbon()"
                    :toValue="old('end_date') ? verta()->parse(old('end_date'))->toCarbon() : verta()->parse($courseWorkflow->end_date)->toCarbon()"
                    :allow-same-day-selection="true"
                />

                <x-multi-select
                    containerClass="col-md-6"
                    id="statuses_to_view"
                    name="statuses_to_view"
                    label="{{__('basemodule::field.statuses_to_view')}}"
                    :items="$viewStatues"
                    :selected="$userViewStatusIds"
                    :multiple="true"
                />

                <x-multi-select
                    containerClass="col-md-6"
                    id="statuses_to_change"
                    name="statuses_to_change"
                    label="{{__('basemodule::field.statuses_to_change')}}"
                    :items="$changeStatues"
                    :selected="$userChangeStatusIds"
                    :multiple="true"
                />

                <x-multi-select
                    containerClass="col-md-6"
                    id="statuses_to_set"
                    name="statuses_to_set"
                    label="{{__('basemodule::field.statuses_to_set')}}"
                    :items="$setStatuses"
                    :selected="$userSetStatusIds"
                    :multiple="true"
                />

                <x-text-area
                    containerClass="col-md-12"
                    name="description"
                    id="description"
                    message-style="message"
                    :label="__('basemodule::field.description')"
                    :max-length="10000"
                    :showMaxLength="true"
                    :trim="true"
                    :rows="4"
                >
                    {!! old('description', $courseWorkflow->description) !!}
                </x-text-area>

                <x-radio-group
                    containerClass="col-md-6"
                    id="is_active"
                    label="{{__('basemodule::field.is_active')}}"
                    name="is_active"
                    :options="convertEnumToArray(\Dornica\Foundation\Core\Enums\IsActive::class, 'basemodule')"
                    :checked="old('is_active',$courseWorkflow->is_active->value)"
                />

            </div>
        </form>

        @canAccess('admin.system-settings.course-settings.course-workflows.update')
        <x-slot:footer>
            <div class="pb-0 px-0 d-flex gap-4 justify-content-end">
                <x-reset-button
                    variant="light"
                    appearance="outline"
                    title="{{__('basemodule::operation.reset')}}"
                    size="md"
                    form-id="edit_course_workflow"
                />
                <div class="btn-submit-wrapper">
                    <x-button
                        title="{{__('basemodule::operation.update.general')}}"
                        type="button"
                        button-type="submit"
                        form-id="edit_course_workflow"
                    />
                </div>
            </div>
        </x-slot:footer>
        @endcanAccess
    </x-card>

    @push("scripts")
        @canAccess('admin.system-settings.course-settings.course-workflows.update')
        {!! FormValidator::formRequest(\Modules\CourseWorkflow\Http\Requests\UpdateCourseWorkflowRequest::class,"#edit_course_workflow") !!}
        @endcanAccess
    @endpush

</x-default-layout>
