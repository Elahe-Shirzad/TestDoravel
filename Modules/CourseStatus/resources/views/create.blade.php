<x-default-layout>
    <x-card>
        <form
            id="create-course-status"
            action="{{route('admin.system-settings.course-settings.course-statuses.store')}}"
            method="POST"
        >
            @csrf
            <div class="row g-3">

                <x-text-input
                    container-class="col-12 col-md-6"
                    name="code"
                    class="dir-ltr text-left english-code"
                    id="code"
                    :label="__('basemodule::field.code')"
                    maxlength="32"
                    value="{{old('code')}}"
                />

                <x-text-input
                    containerClass="col-12 col-md-6"
                    name="name"
                    class="persian-name-without-number"
                    id="name"
                    :label="__('basemodule::field.name')"
                    maxlength="128"
                    value="{{old('name')}}"
                />

                <x-multi-select
                    containerClass="col-12 col-md-6"
                    name="transfer_status_access"
                    id="transfer_status_access"
                    :label="__('basemodule::field.statuses_to_transfer')"
                    maxlength="128"
                    :items="$courseStatuses"
                    value="{{old('transfer_status_access')}}"
                />

                <x-color-input
                    containerClass="col-12 col-md-6"
                    class="custom-color-input"
                    name="color"
                    id="color"
                    :label="__('basemodule::field.color')"
                    maxlength="16"
                    prefix="HEX"
                    value="#20db9ac9"
                />
                <x-radio-group
                    containerClass="col-md-6 {{ $isFirstStatus ? 'text-muted disabled' : '' }}"
                    id="is_start"
                    label="{{__('basemodule::field.statuses.is_start')}}"
                    name="is_start"
                    :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class, 'basemodule')"
                    :checked="getStartDefaultValue($isFirstStatus)"
                    :disabled="$isFirstStatus"
                />
                <x-radio-group
                    containerClass="col-md-6 {{ $isFirstStatus ? 'text-muted disabled' : '' }}"
                    id="is_end"
                    label="{{__('basemodule::field.statuses.is_end')}}"
                    name="is_end"
                    id="is_end"
                    :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class, 'basemodule')"
                    :checked="0"
                    :disabled="$isFirstStatus"
                />
                <x-radio-group
                    containerClass="col-12 col-md-6"
                    name="is_count"
                    id="is_count"
                    :label="__('basemodule::field.statuses.is_count')"
                    :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                    :selectedData="0"
                />
                <x-radio-group
                    containerClass="col-12 col-md-6"
                    name="can_update"
                    id="can_update"
                    :label="__('basemodule::field.statuses.can_update')"
                    :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                    :selectedData="0"
                />
                <x-radio-group
                    containerClass="col-12 col-md-6"
                    name="can_delete"
                    id="can_delete"
                    :label="__('basemodule::field.statuses.can_delete')"
                    :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                    :selectedData="0"
                />
                <x-radio-group
                    containerClass="col-12 col-md-6"
                    name="is_publish"
                    id="is_publish"
                    :label="__('basemodule::field.statuses.is_publish')"
                    :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                    :selectedData="0"
                />
                <x-radio-group
                    containerClass="col-12 col-md-6"
                    name="is_active"
                    id="is_active"
                    :label="__('basemodule::field.is_active')"
                    :options="convertEnumToArray(\Dornica\Foundation\Core\Enums\IsActive::class,'basemodule')"
                    :selectedData="0"
                />

            </div>
            <div class="row g-3">
                <x-text-area
                    containerClass="col-12 col-md-12"
                    name="description"
                    id="description"
                    :label="__('basemodule::field.description')"

                    :max-length="32000"
                    :showMaxLength="true"
                    :trim="true"
                    :rows="4"
                >{!! old('description') !!}</x-text-area>
            </div>
        </form>

        @canAccess('admin.system-settings.course-settings.course-statuses.store')
        <x-slot:footer>
            <div class="pe-0 d-flex gap-4 justify-content-end">
                <x-reset-button
                    title="{{__('basemodule::operation.reset')}}"
                    size="md"
                    variant="light"
                    appearance="outline"
                    form-id="create-course-status"
                />
                <div class="btn-submit-wrapper">
                    <x-button
                        title="{{__('basemodule::operation.submit')}}"
                        type="button"
                        id="submitBtn"
                        button-type="submit"
                        form-id="create-course-status"
                    />
                </div>
            </div>
        </x-slot:footer>
        @endcanAccess
    </x-card>


    @push("scripts")
        @canAccess('admin.system-settings.course-settings.course-statuses.store')
        {!! FormValidator::formRequest(\Modules\CourseStatus\Http\Requests\CourseStatusStoreRequest::class,"#create-course-status") !!}
        @endcanAccess


{{--        <script>--}}
{{--            const activeStatus = "{{ \Modules\BaseModule\Enums\General\BooleanState::YES->value }}";--}}
{{--            const deactiveStatus = "{{ \Modules\BaseModule\Enums\General\BooleanState::NO->value }}";--}}

{{--            const translations = {--}}
{{--                override_status_confirmation: @json(__('basemodule::message.override_status_confirmation', [--}}
{{--        'sectionName' => ':sectionName',--}}
{{--        'targetStatus' => ':targetStatus'--}}
{{--    ])),--}}
{{--                fields: {--}}
{{--                    is_start: @json(__('basemodule::field.statuses.is_start')),--}}
{{--                }--}}
{{--            };--}}

{{--            function getMessageBasedOnField(fieldName, statusTitle) {--}}
{{--                let sectionName = translations.fields[fieldName] || '';--}}
{{--                return replacePlaceholders(translations.override_status_confirmation, {--}}
{{--                    sectionName: sectionName,--}}
{{--                    targetStatus: statusTitle--}}
{{--                });--}}
{{--            }--}}

{{--            function replacePlaceholders(template, replacements) {--}}
{{--                return template.replace(/:([a-zA-Z0-9_]+)/g, function(match, key) {--}}
{{--                    return typeof replacements[key] !== 'undefined' ? replacements[key] : match;--}}
{{--                });--}}
{{--            }--}}


{{--            function checkStartEndConflict() {--}}
{{--                const isStartChecked = $(`[name='is_start']:checked`);--}}
{{--                const isStartActive = isStartChecked.val() === activeStatus;--}}
{{--                const isEndActive = $(`[name='is_end']:checked`).val() === activeStatus;--}}

{{--                if (isStartActive && isEndActive) {--}}
{{--                    return {--}}
{{--                        valid: false,--}}
{{--                        action: () => Swal.fire({--}}
{{--                            html: '{{ __('basemodule::message.start_and_end_status_no_same') }}',--}}
{{--                            icon: 'info',--}}
{{--                            confirmButtonText: '{{ __("basemodule::operation.confirm") }}',--}}
{{--                            buttonsStyling: false,--}}
{{--                            customClass: { confirmButton: 'btn btn-primary' },--}}
{{--                            allowOutsideClick: true,--}}
{{--                        })--}}
{{--                    };--}}
{{--                }--}}

{{--                return { valid: true };--}}
{{--            }--}}

{{--            async function checkAlreadyActiveStatus() {--}}
{{--                const isStartChecked = $(`[name='is_start']:checked`);--}}
{{--                if (isStartChecked.val() !== activeStatus) {--}}
{{--                    return { valid: true };--}}
{{--                }--}}

{{--                try {--}}
{{--                    const response = await $.ajax({--}}
{{--                        url: "{{ route('admin.api.v1.base-module.statuses.check_exist_active_status') }}",--}}
{{--                        type: "post",--}}
{{--                        data: {--}}
{{--                            _token: "{{ csrf_token() }}",--}}
{{--                            field: 'is_start',--}}
{{--                            table: 'course_statuses'--}}
{{--                        }--}}
{{--                    });--}}

{{--                    if (response.data.statusExists) {--}}
{{--                        return {--}}
{{--                            valid: false,--}}
{{--                            action: () => Swal.fire({--}}
{{--                                html: getMessageBasedOnField('is_start', response.data.statusTitle),--}}
{{--                                icon: "info",--}}
{{--                                buttonsStyling: false,--}}
{{--                                showCancelButton: true,--}}
{{--                                confirmButtonText: '{{__('basemodule::operation.confirm')}}',--}}
{{--                                cancelButtonText: '{{__('basemodule::operation.cancel')}}',--}}
{{--                                customClass: {--}}
{{--                                    confirmButton: "btn btn-primary",--}}
{{--                                    cancelButton: 'btn btn-danger'--}}
{{--                                }--}}
{{--                            }).then((result) => {--}}

{{--                                console.log(result)--}}
{{--                                if (result.isConfirmed) {--}}
{{--                                    $('#create-course-status').submit();--}}
{{--                                } else {--}}
{{--                                    $(`[name='is_start'][value='${deactiveStatus}']`).prop("checked", true);--}}
{{--                                }--}}
{{--                                $("button[type='button']").removeClass("disabled");--}}
{{--                            })--}}
{{--                        };--}}
{{--                    }--}}

{{--                    return { valid: true };--}}

{{--                } catch (err) {--}}
{{--                    return { valid: true };--}}
{{--                }--}}
{{--            }--}}

{{--            async function runValidations() {--}}
{{--                const checks = [--}}
{{--                    checkStartEndConflict,--}}
{{--                    checkAlreadyActiveStatus--}}
{{--                ];--}}

{{--                for (const check of checks) {--}}
{{--                    const result = await check();--}}
{{--                    if (!result.valid) {--}}
{{--                        if (typeof result.action === "function") {--}}
{{--                            await result.action();--}}
{{--                        }--}}
{{--                        return false;--}}
{{--                    }--}}
{{--                }--}}

{{--                return true;--}}
{{--            }--}}

{{--            $('#submitBtn').on('click', function(e) {--}}
{{--                e.preventDefault();--}}
{{--                $("button[type='button']").addClass("disabled");--}}

{{--                runValidations().then((allValid) => {--}}
{{--                    $("button[type='button']").removeClass("disabled");--}}

{{--                    if (allValid) {--}}
{{--                        $('#create-course-status').submit();--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        </script>--}}



    @endpush

</x-default-layout>


