<x-default-layout>
    <x-card
        :title="getPageTitle()"
    >
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
                :sortable="true"
                :searchable="true"
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
                name="square"
                label="میدان"
                :sortable="true"
            />
        </x-table>
    </x-card>
</x-default-layout>

