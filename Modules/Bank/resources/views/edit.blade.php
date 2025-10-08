<x-default-layout>

    <x-card
        :title="getPageTitle()"
    >
        <form id="update_bank"
              enctype="multipart/form-data"
              action="{{route('admin.base-information.banks.update',["bank" => encryptValue($bank->id)])}}"
              method="POST"
        >
            @csrf
            @method('PUT')

            <div class="row g-3">

                <x-text-input
                    required
                    container-class="col-12 col-md-6"
                    name="name"
                    label="نام"
                    value="{{old('name',$bank->name)}}"
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
                    value="{{old('code',$bank->code)}}"
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
                    :fromValue="old('published_at') ? verta()->parse(old('published_at'))->toCarbon() : ($bank->published_at ? verta()->parseFormat(jdateFormat(), $bank->published_at)->toCarbon() : null)"
                    :toValue="old('expired_at') ? verta()->parse(old('expired_at'))->toCarbon() : ($bank->expired_at ? verta()->parseFormat(jdateFormat(), $bank->expired_at)->toCarbon() : null)"
{{--                    :fromValue="old('published_at') ? verta()->parse(old('published_at'))->toCarbon() : null"--}}
{{--                    :toValue="old('expired_at') ? verta()->parse(old('expired_at'))->toCarbon() : null"--}}
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
                    value="{{old('color',$bank->color)}}"
                />

                <x-radio-group
                    container-class="col-12 col-md-6"
                    id="type"
                    name="type"
                    label="نوع بانک"
                    :options="convertEnumToArray(\Modules\Bank\Enums\BankType::class, 'bank')"
                    :checked="old('is_active',$bank->type->value)"
                />

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


                <x-switch-input
                    container-class="col-12 col-md-6"
                    class="is_active"
                    name="is_active"
                    label="وضعیت(فعال، غیرفعال)"
                    :onValue="\Dornica\Foundation\Core\Enums\IsActive::YES->value"
                    :offValue="\Dornica\Foundation\Core\Enums\IsActive::NO->value"
                    :data-entity-id="(\Dornica\Foundation\Core\Enums\IsActive::class)"
                    :checked="old('is_active',$bank->is_active->value)"

                />

                <x-editor
                    name="description"
                    type="tinymce"
                    :label="__('bank::general.description')"
                    mode="full"
                    :max-length="1024"
                >{!!old('description',$bank->description)!!}</x-editor>

                <x-number-input
                    container-class="col-md-6"
                    name="sort"
                    label="مرتب سازی"
                    value="{{old('sort',$bank->sort)}}"
                    min="1"
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
                    :src="$bank->image_id"
                />

            </div>




            @canAccess('admin.base-information.banks.update')
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
    @push("scripts")
        @canAccess('admin.base-information.banks.update')
    {!! FormValidator::formRequest(\Modules\Bank\Http\Requests\UpdateRequest::class,"#update_bank") !!}
        @endcanAccess
    @endpush

</x-default-layout>

