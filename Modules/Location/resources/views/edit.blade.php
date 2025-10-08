@php use Dornica\Foundation\Core\Enums\IsActive; @endphp
<x-default-layout>
    <x-card>
        <form
            id="edit-location"
            method="POST"
            action="{{route('admin.base-information.locations.update',["location" => encryptValue($location->id)])}}"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')
            <div
                class="row g-3 mb-2"
            >
                <x-select
                    container-class="col-12 col-md-6"
                    name="is_active"
                    :label="__('location::general.is_active')"
                    :selected="old('is_active',$location->is_active->value)"
                    :items="$isActive"
                />

                <x-number-input
                    container-class="col-12 col-md-6"
                    name="sort"
                    label="مرتب سازی"
                    value="{{old('sort',$location->sort)}}"
                    min="1"
                />

            </div>

            <div
                class="row g-3"
            >
                <x-text-input
                    maxlength="64"
                    required
                    container-class="col-12 col-md-6"
                    name="branch"
                    id="branch"
                    :label="__('location::general.branch')"
                    :trim="true"
                    value="{{old('branch',$location->branch)}}"
                />
                <x-text-input
                    maxlength="64"
                    required
                    container-class="col-12 col-md-6"
                    name="square"
                    id="square"
                    :label="__('location::general.square')"
                    value="{{old('square',$location->square)}}"
                />

                <x-text-input
                    maxlength="64"
                    container-class="col-12 col-md-6"
                    name="street"
                    id="street"
                    :label="__('location::general.street')"
                    value="{{old('street',$location->street)}}"
                />

                <x-text-input
                    maxlength="64"
                    container-class="col-12 col-md-6"
                    name="alley"
                    id="alley"
                    :label="__('location::general.alley')"
                    value="{{old('alley',$location->alley)}}"
                />

                <x-text-input
                    maxlength="256"
                    container-class="col-12 col-md-12"
                    name="full_address"
                    id="full_address"
                    :label="__('location::general.full_address')"
                    :readOnly="true"
                    value="{{old('full_address',$location->full_address)}}"
                />



                {{--                <x-radio-group--}}
                {{--                    container-class="col-12 col-md-6"--}}
                {{--                    name="is_active"--}}
                {{--                    :label="__('location::general.is_active')"--}}
                {{--                    id="is_active"--}}
                {{--                    :selected="old('is_active')"--}}
                {{--                    :options="convertEnumToArray(IsActive::class, 'location')"--}}
                {{--                    :checked="IsActive::YES->value"--}}
                {{--                />--}}


                {{--                <x-switch-input--}}
                {{--                    container-class="col-12 col-md-6"--}}
                {{--                    name="is_active"--}}
                {{--                    :label="__('location::general.is_active')"--}}
                {{--                    :data-entity-id="(IsActive::class)"--}}
                {{--                    :onValue="IsActive::YES->value"--}}
                {{--                    :offValue="IsActive::NO->value"--}}
                {{--                    :checked="IsActive::YES->value"--}}
                {{--                />--}}


                <x-color-input
                    container-class="col-12 col-md-6 custom-color-input"
                    name="color"
                    max-length="16"
                    label="{{__('location::general.color')}}"
                    id="color"
                    value="#ff0000"
                    prefix="HEX"
                    message-style="message"
                    message-type="error"
                    value="{{old('color',$location->color)}}"
                />

                <x-radio-group
                    required
                    container-class="col-12 col-md-6"
                    name="service"
                    :label="__('location::general.service')"
                    id="service"
                    :checked="old('service',$location->service->value)"
                    :options="convertEnumToArray(\Modules\Location\Enums\Service::class, 'location')"
                />


                <x-datetime-range-picker
                    container-class="col-md-12"
                    type="datetime"
                    fromName="published_at"
                    toName="expired_at"
                    :time-picker-options="['seconds' => false, 'minutes' => true, 'hours' => true]"
                    :fromLabel="__('location::general.published_at')"
                    :toLabel="__('location::general.expired_at')"
                    :autoClose="true"
                    :allowTyping="true"
                    :from-min-date="now()"
                    :fromValue="old('published_at') ? verta()->parse(old('published_at'))->toCarbon() : ($location->published_at ? verta()->parseFormat(jdateFormat(), $location->published_at)->toCarbon() : null)"
                    :toValue="old('expired_at') ? verta()->parse(old('expired_at'))->toCarbon() : ($location->expired_at ? verta()->parseFormat(jdateFormat(), $location->expired_at)->toCarbon() : null)"

                />

                <x-text-area
                    name="description"
                    type="tinymce"
                    :label="__('location::general.description')"
                    mode="full"
                    :max-length="1024"
                >{!!old('description',$location->description)!!}</x-text-area>
            </div>


            <x-image-picker
                name="avatar"
                :label="__('location::general.location_image')"
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
                :src="$location->avatar_id"
            />

            @canAccess('admin.base-information.locations.store')
            <div class="card-footer pb-0 px-0 d-flex gap-4 pt-5 justify-content-end mt-4">

                <x-reset-button
                    title="بازنویسی"
                    variant="light"
                    appearance="outline"
                />
                <x-button
                    button-type="submit"
                    title="ثبت تغییرات"
                />
            </div>
            @endcanAccess

        </form>
    </x-card>

    @push("scripts")
        @canAccess('admin.base-information.locations.store')
        {!! FormValidator::formRequest(\Modules\Location\Http\Requests\LocationUpdateRequest::class,"#edit-location") !!}
        @endcanAccess

        <script>
            $(function () {
                // Array of fields related to the address
                const fields = [
                    { id: 'branch', label: 'شعبه' },
                    { id: 'square', label: 'میدان' },
                    { id: 'street', label: 'خیابان' },
                    { id: 'alley', label: 'کوچه' },
                ];

                const $fullAddress = $('#full_address');

                // Function to build the full address
                const buildFullAddress = () => {
                    const parts = fields.map(f => {
                        const value = $(`#${f.id}`).val()?.trim();
                        return value ? `${f.label} ${value}` : '';
                    }).filter(Boolean);

                    const fullAddress = parts.join('، ');
                    $fullAddress.val(fullAddress).prop('readonly', true);
                };

                // Build the full address whenever any of the fields change
                $(fields.map(f => `#${f.id}`).join(',')).on('input change', buildFullAddress);

                // Also build on initial load (if there is old() data)
                buildFullAddress();
            });
        </script>
    @endpush


</x-default-layout>
