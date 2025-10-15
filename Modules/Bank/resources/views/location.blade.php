<x-default-layout>
    <x-card
        :title="getPageTitle()"
    >
        <x-slot:headerActions>
            <x-button
                type="link"
                icon="fa-regular fa-pen-to-square fs-20"
{{--                data-bs-target="#teacher-skill-modal-create"--}}
{{--                data-bs-toggle="modal"--}}
                container-class="d-flex justify-content-end"
                title="افزودن/حذف گروهی(شعبه)"
                variant="primary"
                appearance="light"
                size="xs"
                type="link"
                :href="route('admin.base-information.banks.locations.edit', encryptValue($bank->id))"
            />
        </x-slot:headerActions>
        <x-table
            id="table-sample"
            :modifier-closure="$tableModifierClosure"
            :data-source="$tableDataSource"
            :per-page="5"
        >

            <x-table.column
                name="id"
                label="شناسه"
                :sortable="true"
            />
            <x-table.column
                name="teacher_full_name"
                label="تصویر"
                width="40%"
            >
                <div class="d-flex align-items-center gap-2 justify-content-center">
                    <x-image
                        src="$row[bank_location_avatar_url]"
                        alt="تصویر"
                        title="تصویر"
                        :showPopup="true"
                        width="40"
                        height="40"
                        containerClass="rounded-circle"

                    />
                </div>
            </x-table.column>

            <x-table.column
                name="branch"
                label="شعبه"
                :sortable="true"
            />

            <x-table.column
                name="service"
                :label="__('location::general.service')"
                :sortable="true"
            >
                <div
                    condition="$row[service] === {{ \Modules\Location\Enums\Service::ONLINE->value }}">
                    <x-badge
                        appearance="light"
                        variant="danger"
                        :value="__('location::enum.service.online')"
                        size="xs"
                    />
                </div>

                <div
                    condition="$row[service] === {{ \Modules\Location\Enums\Service::OFFLINE->value }}">
                    <x-badge
                        appearance="light"
                        variant="success"
                        :value="__('location::enum.service.offline')"
                        size="xs"
                    />
                </div>
            </x-table.column>

            <x-table.column
                name="square"
                label="میدان"
                :sortable="true"
            />
            <x-table.column
                label="عملیات"
            >
                <x-button
                    type="link"
                    variant="danger"
                    appearance="transparent"
                    tooltip="{{ __('location::general.destroy') }}"
                    icon="far fa-trash fs-16"
                    :confirmation="true"
                    confirmation-icon="far fa-trash"
                    confirmationMessage="{{ __('location::general.delete_confirmation_message') }}"
                    confirmation-type="danger"
                    href="$row[delete_route]"
                    method="delete"
                    size="xs"
                />
            </x-table.column>
        </x-table>

    </x-card>
</x-default-layout>

