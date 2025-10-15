<x-default-layout>

    <x-card
        :title="getPageTitle()"
    >
        <form id="update_bank_location"
              enctype="multipart/form-data"
              action="{{route('admin.base-information.banks.locations.update',["bank" => encryptValue($bank->id)])}}"
              method="POST"
        >
            @csrf
            @method('PUT')

            <div class="row g-3">
                <x-multi-select
                    required
                    containerClass="col-md-6"
                    id="location_id"
                    name="location_id"
                    label="شعبات بانک"
                    :items="$locations"
                    :selected="$locationsSelected"
                    :multiple="true"
                />
            </div>


            @canAccess('admin.base-information.banks.locations.update')
            <div class="card-footer pb-0 px-0 d-flex gap-4 pt-5 justify-content-end mt-4">

                <x-reset-button
                    title="بازنویسی"
                    variant="light"
                    appearance="outline"
                />
                <x-button
                    button-type="submit"
                    title="ذخیره تغییرات"
                />
            </div>
            @endcanAccess
        </form>

    </x-card>
{{--    @push("scripts")--}}
{{--        @canAccess('admin.base-information.banks.update')--}}
{{--        {!! FormValidator::formRequest(\Modules\Bank\Http\Requests\UpdateRequest::class,"#update_bank") !!}--}}
{{--        @endcanAccess--}}
{{--    @endpush--}}

</x-default-layout>
