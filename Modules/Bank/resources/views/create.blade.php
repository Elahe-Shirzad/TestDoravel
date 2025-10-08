<x-default-layout>

    <x-card
    :title="getPageTitle()"
    >
        <form id="create_bank" action="{{route('admin.base-information.banks.store')}}"
              enctype="multipart/form-data"
              method="POST">
            @csrf
            <div class="row g-3">

                <x-text-input
                    required
                    container-class="col-12 col-md-6"
                    name="name"
                    id="name"
                    label="نام"
                    value="{{old('name')}}"
                />

                <x-text-input
                    required
                    container-class="col-12 col-md-6"
                    text-align="left"
                    class="dir-ltr"
                    name="code"
                    id="code"
                    :maxLength="8"
                    :label="__('bank::general.code')"
                    :value="old('code')"
                    :trim="true"
                />

                <x-datetime-range-picker
                    container-class="col-md-12"
                    type="datetime"
                    fromName="published_at"
                    toName="expired_at"
                    :time-picker-options="['seconds' => false, 'minutes' => true, 'hours' => true]"
                    :fromLabel="__('bank::general.published_at')"
                    :toLabel="__('bank::general.expired_at')"
                    :autoClose="true"
                    :allowTyping="true"
                    :from-min-date="now()"
                    :fromValue="old('published_at') ? verta()->parse(old('published_at'))->toCarbon() : null"
                    :toValue="old('expired_at') ? verta()->parse(old('expired_at'))->toCarbon() : null"
                />

                <x-color-input
                    container-class="col-12 col-md-6"
                    name="color"
                    max-length="16"
                    label="{{__('bank::general.color')}}"
                    id="color"
                    value="#ff0000"
                    prefix="HEX"
                    message-style="message"
                    message-type="error"
                    container-class="col-md-6"
                    class="custom-color-input"
                />

                <x-radio-group
                    container-class="col-12 col-md-6"
                    id="type"
                    name="type"
                    label="نوع بانک"
                    :options="convertEnumToArray(\Modules\Bank\Enums\BankType::class, 'bank')"
                    :checked="\Modules\Bank\Enums\BankType::Government->value"
                />
                <x-image-picker
                    name="image"
                    :label="__('bank::general.bank_image')"
                    :accept="$avatarFileType['mimes'] ? convertExtensionsToDotFormat($avatarFileType['mimes']) : '.jpg,.png'"
                    :allowed-extensions="$avatarFileType['mimes']"
                    :max-size="$avatarFileType['maxFileSize']"
                    :allowRename="true"
                    :cropper="true"
                    :allow-recropping="true"
                    :allowRename="true"
                    :clearable="$avatarFileType['isRequired'] == 'nullable'"
                    :aspect-ratio="2/2"
                    :file-name-max-length="128"
                />

                <x-multi-select
                    required
                    containerClass="col-md-6"
                    id="location_id"
                    name="location_id"
                    label="شعبات بانک"
                    :items="$locations"
                    :selected="old('location_id')"
                    :multiple="true"
                />


                <x-switch-input
                    container-class="col-12 col-md-6"
                    class="is_active"
                    name="is_active"
                    label="وضعیت(فعال، غیرفعال)"
                    :onValue="\Dornica\Foundation\Core\Enums\IsActive::YES->value"
                    :offValue="\Dornica\Foundation\Core\Enums\IsActive::NO->value"
                    :data-entity-id="(\Dornica\Foundation\Core\Enums\IsActive::class)"
                    :checked="\Dornica\Foundation\Core\Enums\IsActive::YES->value"

                />

                <x-editor
                    name="description"
                    type="tinymce"
                    :label="__('bank::general.description')"
                    mode="full"
                    :max-length="1024"
                > {!!old('description')!!} </x-editor>

            </div>


            @canAccess('admin.base-information.banks.store')
                <div class="card-footer pb-0 px-0 d-flex gap-4 pt-5 justify-content-end mt-4">

                    <x-reset-button
                    title="بازنویسی"
                    variant="light"
                    appearance="outline"
                    />
                    <x-button
                    button-type="submit"
                    title="ثبت اطلاعات"
                    />
                </div>
            @endcanAccess
        </form>

    </x-card>

    @push("scripts")
        @canAccess('admin.base-information.banks.store')
        {!! FormValidator::formRequest(\Modules\Bank\Http\Requests\StoreRequest::class,"#create_bank") !!}
        @endcanAccess

            <script>
                $(function () {
                    $('#name').on('keydown keyup change', function () {
                        $('#code').val($(this).val().substring(0, 128));
                    });

                    // $('#small_description').on('keydown keyup change', function () {
                    //     const cleaned = cleanText($(this).val());
                    //     let text = cleaned.substring(0, 255);
                    //
                    //     // Update the span content correctly
                    //     updateComponentCounter('.seo_description_container', text);
                    //
                    //     // Set cleaned text back to textarea
                    //     $('#seo_description').val(text);
                    // });
                });
            </script>
    @endpush

</x-default-layout>
