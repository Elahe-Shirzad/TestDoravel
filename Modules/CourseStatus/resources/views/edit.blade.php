<x-default-layout>
    <x-card>
        <form
            id="edit-course-status"
            action="{{route('admin.system-settings.course-settings.course-statuses.update',encryptValue($status->id))}}"
            method="POST"
        >
            @csrf
            @method('PUT')
            <div class="row g-3">

                <x-text-input
                    id="code"
                    name="code"
                    :label="__('basemodule::field.code')"
                    value="{{old('code',$status->code)}}"
                    containerClass="col-12 col-md-6 {{ $isLocked ? 'text-mute': ''}}"
                    class="dir-ltr text-left english-code"
                    maxlength="32"
                    :disabled="$isLocked"
                />

                <x-text-input
                    required
                    id="name"
                    name="name"
                    :label="__('basemodule::field.name')"
                    value="{{old('name',$status->name)}}"
                    containerClass="col-12 col-md-6"
                    class="persian-name-without-number"
                    maxlength="128"
                />
                <x-multi-select
                id="transfer_status_access"
                name="transfer_status_access"
                :label="__('basemodule::field.transfer_status_access')"
                :items="old('transfer_status_access',$allCourseStatuses)"
                :selected="$childCourseStatusIds"
                containerClass="col-12 col-md-6 {{$isLocked ? 'text-mute' : ''}}"
                :disabled="$isLocked"
                />

                <x-color-input
                    id="color"
                    name="color"
                    prefix="HEX"
                    :label="__('basemodule::field.color')"
                    value="{{old('code',$status->color)}}"
                    message-style="message"
                    message-type="error"
                    class="custom-color-input"
                    containerClass="col-12 col-md-6"
                    maxlength="16"
                />

                <x-radio-group
                id="is_start"
                name="is_start"
                :label="__('basemodule::field.statuses.is_start')"
                :checked="old('is_start',$status->is_start->value)"
                :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                containerClass="col-12 col-md-6 {{ $isLocked || $isStartStatus ? 'text-mute' : ''}}"
                :disabled="($isLocked || $isStartStatus)"
                />

                <x-radio-group
                id="is_end"
                name="is_end"
                :label="__('basemodule::field.statuses.is_end')"
                :checked="old('is_end',$status->is_end->value)"
                :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                containerClass="col-12 col-md-6 {{ $isLocked || $isStartStatus ? 'text-mute' : ''}}"
                :disabled="($isLocked || $isStartStatus)"
                />

                <x-radio-group
                id="is_count"
                name="is_count"
                :label="__('basemodule::field.statuses.is_count')"
                :checked="old('is_count',$status->is_count->value)"
                :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                containerClass="col-12 col-md-6 {{ $isLocked ? 'text-mute' : ''}}"
                :disabled="$isLocked"
                />

                <x-radio-group
                id="can_update"
                name="can_update"
                :label="__('basemodule::field.statuses.can_update')"
                :checked="old('can_update',$status->can_update->value)"
                :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                containerClass="col-12 col-md-6 {{ $isLocked ? 'text-mute' : ''}}"
                :disabled="$isLocked"
                />

                <x-radio-group
                id="can_delete"
                name="can_delete"
                :label="__('basemodule::field.statuses.can_delete')"
                :checked="old('can_delete',$status->can_delete->value)"
                :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                containerClass="col-12 col-md-6 {{ $isLocked ? 'text-mute' : ''}}"
                :disabled="$isLocked"
                />

                <x-radio-group
                id="is_publish"
                name="is_publish"
                :label="__('basemodule::field.statuses.is_publish')"
                :checked="old('is_publish',$status->is_publish->value)"
                :options="convertEnumToArray(\Modules\BaseModule\Enums\General\BooleanState::class,'basemodule')"
                containerClass="col-12 col-md-6 {{ $isLocked ? 'text-mute' : ''}}"
                :disabled="$isLocked"
                />

                <x-radio-group
                id="is_active"
                name="is_active"
                :label="__('basemodule::field.is_active')"
                :checked="old('is_active',$status->is_active->value)"
                :options="convertEnumToArray(\Dornica\Foundation\Core\Enums\IsActive::class,'basemodule')"
                containerClass="col-12 col-md-12 {{ $isLocked || $isStartStatus ? 'text-mute' : ''}}"
                :disabled="($isLocked || $isStartStatus)"
                />

                    <x-number-input
                        id="sort"
                        name="sort"
                        :label="__('basemodule::field.sort')"
                        value="{{old('sort',$status->sort)}}"
                        containerClass="col-12 col-md-6 {{ $isLocked ? 'text-mute': ''}}"
                        min="1"
                        show-separator="true"
                        :disabled="$isLocked"
                    />
                <x-text-area
                        id="description"
                        name="description"
                        :label="__('basemodule::field.description')"
                        value="{{old('description',$status->description)}}"
                        containerClass="col-12 col-md-12 {{ $isLocked ? 'text-mute': ''}}"
                        maxlength="32000"
                        show-max-length="true"
                        :disabled="$isLocked"
                    />
            </div>

        </form>
        @canAccess('admin.system-settings.course-settings.course-statuses.update')
        <x-slot:footer>
            <div class="pe-0 d-flex gap-4 justify-content-end">
                <x-reset-button
                    title="{{__('basemodule::operation.reset')}}"
                    size="md"
                    variant="light"
                    appearance="outline"
                    form-id="edit-course-status"
                />
                <div class="btn-submit-wrapper">
                    <x-button
                        title="{{__('basemodule::operation.update.general')}}"
                        type="button"
                        id="submitBtn"
                        button-type="submit"
                        form-id="edit-course-status"
                    />
                </div>
            </div>
        </x-slot:footer>
        @endcanAccess
    </x-card>

    @push("scripts")
        @canAccess('admin.system-settings.course-settings.course-statuses.update')
        {!! FormValidator::formRequest(\Modules\CourseStatus\Http\Requests\CourseStatusUpdateRequest::class,"#edit-course-status") !!}
        @endcanAccess

        <script>
            const activeStatus = "{{ \Modules\BaseModule\Enums\General\BooleanState::YES->value }}";
            const deactiveStatus = "{{ \Modules\BaseModule\Enums\General\BooleanState::NO->value }}";
            const translations = {
                override_status_confirmation: @json(__('basemodule::message.override_status_confirmation', [
            'sectionName' => ':sectionName',
            'targetStatus' => ':targetStatus'
        ])),
                fields: {
                    is_start: @json(__('basemodule::field.statuses.is_start')),
                    is_end: @json(__('basemodule::field.statuses.is_end')),
                }
            };

            async function runValidations() {
                const checks = [
                    checkStartEndConflict,
                    checkAlreadyActiveStatus
                ];

                for (const check of checks) {
                    const result = await check();
                    if (!result.valid) {
                        if (typeof result.action === "function") {
                            await result.action();
                        }
                        return false;
                    }
                }

                return true;
            }

            $('#submitBtn').on('click', function (e) {
                e.preventDefault();
                $("button[type='button']").addClass("disabled");

                runValidations().then((allValid) => {
                    $("button[type='button']").removeClass("disabled");

                    if (allValid) {
                        $('#edit-course-status').submit();
                    }
                });
            });

            function checkStartEndConflict() {
                const isStartChecked = $(`[name='is_start']:checked`);
                const isStartActive = isStartChecked.val() === activeStatus;
                const isEndActive = $(`[name='is_end']:checked`).val() === activeStatus;

                if (isStartActive && isEndActive) {
                    return {
                        valid: false,
                        action: () => fireSwal({
                            html: '{{ __('basemodule::message.start_and_end_status_no_same') }}',
                            showCancelButton: false
                        })
                    };
                }

                return {valid: true};
            }

            async function checkAlreadyActiveStatus() {
                const isStartChecked = $(`[name='is_start']:checked`);
                const alreadyStartStatus = "{{ $status->is_start === \Modules\BaseModule\Enums\General\BooleanState::YES }}";
                if (isStartChecked.val() !== activeStatus ||
                    (isStartChecked.val() === activeStatus && alreadyStartStatus)) {
                    return {valid: true};
                }

                try {
                    const response = await $.ajax({
                        url: "{{ route('admin.api.v1.base-module.statuses.check_exist_active_status') }}",
                        type: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            field: 'is_start',
                            table: 'course_statuses'
                        }
                    });

                    const isActiveChecked = $(`[name="is_active"]:checked`).val() === activeStatus;

                    if (response.data.statusExists || !isActiveChecked) {
                        let message = "";

                        if (response.data.statusExists) {
                            message += getMessageBasedOnField('is_start', response.data.statusTitle) + "<br>";
                        }
                        if (!isActiveChecked) {
                            message += `<div style="margin-top:8px;
                             font-size:12px;
                             color:#555;
                             padding:6px 8px;">
با انتخاب این وضعیت به عنوان شروع، وضعیت دوره نیز به صورت خودکار فعال خواهد شد.
                </div>`;
                        }

                        return {
                            valid: false,
                            action: () => fireSwal({
                                html: message,
                                showCancelButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $(`[name="is_active"][value="${activeStatus}"]`).prop('checked', true);
                                    $('#edit-course-status').submit();
                                } else {
                                    $(`[name='is_start'][value='${deactiveStatus}']`).prop("checked", true);
                                }
                                $("button[type='button']").removeClass("disabled");
                            })
                        };
                    }

                    return {valid: true};

                } catch (err) {
                    return {valid: true};
                }
            }

            /// FUNCTIONS ///
            function getMessageBasedOnField(fieldName, statusTitle) {
                let sectionName = translations.fields[fieldName] || '';

                return replacePlaceholders(translations.override_status_confirmation, {
                    sectionName: sectionName,
                    targetStatus: statusTitle
                });
            }

            function replacePlaceholders(template, replacements) {
                return template.replace(/:([a-zA-Z0-9_]+)/g, function (match, key) {
                    return typeof replacements[key] !== 'undefined' ? replacements[key] : match;
                });
            }

            function fireSwal(options) {
                const defaultOptions = {
                    icon: 'info',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: '{{__('basemodule::operation.confirm')}}',
                    cancelButtonText: '{{__('basemodule::operation.cancel')}}',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: 'btn btn-danger'
                    }
                };
                return Swal.fire({...defaultOptions, ...options});
            }
        </script>





    @endpush
</x-default-layout>
