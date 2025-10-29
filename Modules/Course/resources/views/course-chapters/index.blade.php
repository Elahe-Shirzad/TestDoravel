<x-default-layout>
    <x-card
        :title="__('basemodule::section.course-chapters')"
        class="mb-5"
    >

        <div
            class="accordion my-5"
            id="x-filter-accordion"
        >
            <div class="accordion-item bg-gray-100 border-0">
                <h2 class="accordion-header">
                    <div
                        class="accordion-button d-flex align-items-stretch justify-content-end flex-wrap gap-2 bg-gray-100 cursor-default collapsed">
                        <x-button
                            class="x-filter-btn-collapse btn-collapse collapsed"
                            variant="info"
                            appearance="light"
                            data-bs-toggle="collapse"
                            data-bs-target="#x-filter-accordion-heading"
                            title="فیلتر"
                            icon="fas fa-chevron-down fs-11"
                        />
                    </div>
                </h2>

                <div
                    id="x-filter-accordion-heading"
                    class="accordion-collapse collapse"
                    data-bs-parent="#x-filter-accordion"
                >
                    <div class="accordion-body">
                        <form
                            id="table_chapter_search"
                            class="row g-4 mb-10"
                            data-table-target="table_chapter"
                            action="{{ route('admin.courses.chapters.search', ['course'=>encryptValue($course->id)]) }}"
                            method="post"
                        >

                            <x-text-input
                                container-class="col-md-6"
                                id="title"
                                name="title"
                                label="{{ __('basemodule::field.title') }}"
                                data-table-filter-operator="like"
                            />

                            <x-select
                                containerClass="col-md-6"
                                id="is_active"
                                name="is_active"
                                :label="__('basemodule::field.status')"
                                :items="$isActiveData"
                            />

                            <div class="d-flex flex-end align-items-center gap-3 mt-5">
                                <div class="btn-submit-wrapper">
                                    <x-reset-button
                                        title="{{ __('basemodule::operation.reset') }}"
                                        variant="light"
                                        appearance="outline"
                                        form-id="table-chapters-search"
                                    />
                                </div>

                                <x-button
                                    id="table_chapter_search_button"
                                    containerClass="text-end"
                                    title="فیلتر اطلاعات"
                                    button-type="submit"
                                    style="height: 46px"
                                    form-id="table_chapter_search"
                                />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <x-table
            id="table_chapter"
            :data-source="$tableDataSource"
            :modifier-closure="$modifierClosure"
            :per-page="10"
{{--            action="{{route('admin.courses.chapters.index')}}"--}}
        >
            <x-table.column
                name="title"
                label="{{ __('basemodule::field.title') }}"
                :sortable="true"
                searchable
            />

            <x-table.column
                name="total_course_content"
                label="{{ __('course::field.total_course_content') }}"
                :sortable="true"
                searchable
            />

            <x-table.column
                name="sort"
                label="{{ __('basemodule::field.sort') }}"
                :sortable="true"
                searchable
            />

            <x-table.column
                name="is_active"
                label="{{ __('basemodule::field.status') }}"
                :sortable="true"
                :badge-map="[
                    0 => [
                        'text' => __('basemodule::field.deactive'),
                        'variant' => 'danger',
                        'appearance' => 'light',
                    ],
                    1 => [
                        'text' => __('basemodule::field.active'),
                        'variant' => 'success',
                        'appearance' => 'light',
                    ]
                ]"
                searchable
            />

            <x-table.column width="100px">
                <x-dropdown-button
                    {{-- ToDo: Infra Update - Check why the action button doesn't show up --}}
                    appearance="light"
                    variant="primary"
                    :title="__('basemodule::operation.operations')"
                    icon="fa-regular fa-ellipsis-vertical"
                    :closeOnClick="false"
                    menuPosition="right"
                    size="sm"
                >
                    <x-dropdown-item
                        :title="__('basemodule::operation.show')"
                        variant="success"
                        href="$row[show_route]"
                    />

                    <x-dropdown-item
                        :title="__('basemodule::operation.edit.general')"
                        variant="success"
                        href="$row[edit_route]"
                    />

{{--                    <x-dropdown-item--}}
{{--                        :title="__('basemodule::field.sessions')"--}}
{{--                        variant="success"--}}
{{--                        href="$row[subject_content_route]"--}}
{{--                    />--}}

                    <x-dropdown-item
                        :title="__('basemodule::operation.destroy')"
                        elementClass="text-danger"
                        :confirmation="true"
                        method="DELETE"
                        disabled="$row[has_content]"
                        disabled-tooltip="$row[dependency]"
                        variant="danger"
                        confirmation-type="danger"
                        confirmation-icon="fa-regular fa-trash-can"
                        :confirm-button-text="__('basemodule::operation.destroy')"
                        :confirmation-message="__('basemodule::message.delete_confirmation_message')"
                        href="$row[delete_route]"
                    />
                </x-dropdown-button>
            </x-table.column>
        </x-table>

    </x-card>
</x-default-layout>
