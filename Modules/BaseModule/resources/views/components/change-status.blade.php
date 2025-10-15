<x-modal
    :title="$modalTitle"
    size="lg"
    :id="$modalId"
    on-close-action="clear"
    :closable="false"
>
    @if(!$changeStatusInList)
        @if(count($modalBadges))
            <x-slot:title_badges>
                @foreach($modalBadges as $badgeInfo)
                    <x-badge
                        :value="$badgeInfo['value']"
                        :variant="$badgeInfo['variant']"
                        appearance="light"
                        :class="$badgeInfo['class'] ?? ''"
                    />
                @endforeach
            </x-slot:title_badges>
        @endif

    @else
        <x-slot:title_badges>
            <div id="entity-info">

            </div>
        </x-slot:title_badges>
    @endif

    <form
        id="{{ $formId }}"
        action="{{ !$changeStatusInList ? route($routeName, is_array($parameterName) ? $parameterName : [$parameterName => encryptValue($modelId)]) : '' }}"
        method="post"
    >
        @csrf
        @method('PATCH')

        @php
            $workflowStatuses = getUserCurrentRoleWorkflow($sectionNameInWorkflow);
            $setStatuses = $workflowStatuses->get('set', collect());
            $status = $statusModel::find($statusId);
            $sectionStatusAccesses = $status
                ? $status->{$statusAccessesRelation}()->pluck("child_{$sectionNameInWorkflow}_status_id")->toArray()
                : [];
            $statusForSet = $setStatuses->intersect($sectionStatusAccesses);
            $viewStatuses = prepareSelectComponentData(
                $statusModel::whereIn('id', $statusForSet->all())->where('is_active', \Dornica\Foundation\Core\Enums\IsActive::YES)->get()
            );
        @endphp
        <div class="row gy-4">
            <x-select
                containerClass="col-12 col-md-8 col-lg-6"
                :label="__('basemodule::field.status')"
                id="status-selector"
                :name="$selectBoxName"
                :Items="$viewStatuses"
            />

            <x-text-area
                :label="__('basemodule::field.description')"
                name="description"
                id="description"
                :max-length="32000"
                :trim="true"
                :resizable="false"
                :showMaxLength="true"
                :autoSizing="true"
            />
        </div>
    </form>

    @canAccess($routeName)
    <x-slot:footer>
        <div class="pb-0 px-0 d-flex gap-4 justify-content-end">
            <div class="d-flex gap-4 justify-content-end">
                <x-reset-button
                    :title="__('basemodule::operation.reset')"
                    type="button"
                    :formId="$formId"
                    variant="light"
                    appearance="outline"
                    id="change-status-reset-button"
                />

                <x-button
                    :title="__('basemodule::operation.store.general')"
                    type="button"
                    :formId="$formId"
                    button-type="submit"
                    class="btn_submit"
                    id="change-status-button"
                />
            </div>
        </div>
    </x-slot:footer>
    @endcanAccess
</x-modal>

{{--@pushonce('head_scripts')--}}
{{--    @routes--}}
{{--@endpushonce--}}

@push('scripts')
    @canAccess($routeName)
    {!! FormValidator::formRequest($formRequest, "#{$formId}") !!}
    @endcanAccess

    @if($changeStatusInList)
        <script>
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const statusURL = "{{ route('admin.api.v1.base-module.statuses.set-statuses-for-select-box') }}";
            const form = $('#{{ $formId }}');
            const changeButton = $('#change-status-button');
            const resetButton = $('#change-status-reset-button');
            const section = "{{ $sectionNameInWorkflow }}";
            const statusAccessesRelation = "{{ $statusAccessesRelation }}";
            const errorMessage = "{{ __('basemodule::message.error_occurred') }}";
            const changeStatusInList = "{{ $changeStatusInList }}";
            let relationsWith = @json($relationsInResource);
            let relations = relationsWith.relations;
            let relationsColors = relationsWith.colors;
            let fieldsForBadge = @json($fieldsForBadge);

            // setters functions
            const setText = (selector, value) => $(selector).text(value ?? '-');
            const setHtml = (selector, value) => $(selector).html(value ?? '-');
            const appendHtml = (selector, value) => $(selector).append(value ?? '-');
            const setValue = (selector, value) => $(selector).val(value ?? '');

            function colorClassToBorderStyle(className) {
                const map = {
                    'text-bg-light-warning': '#ffe08a',
                    'text-bg-light-danger': '#f5a6ab',
                    'text-bg-light-success': '#8fcfad',
                    'text-bg-light-primary': '#98bfff',
                    'text-bg-light-info': '#aef0fc',
                    'text-bg-light-info-2': '#c5abe4',
                    'text-bg-light-secondary': '#c1c3c5'
                };

                return map[className] ? `border:1px solid ${map[className]};` : '';
            }

            const showEntityDetail = (id, entity) => {
                $.post("{{ route('admin.api.v1.base-module.interaction.show') }}", {
                    _token: csrfToken,
                    id: id,
                    resource_type: section,
                    interaction_type: "{{ \Modules\BaseModule\Enums\General\InteractionType::JUST_MODEL->value }}",
                    relations_with: relations
                }).done(({data}) => {
                    if (fieldsForBadge.length > 0) {
                        $.each(fieldsForBadge, function (index, item) {
                            let fieldName = item.field;
                            let variant = item.variant;
                            let fieldValue = data[fieldName];
                            let borderStyle = colorClassToBorderStyle(variant);
                            let classes = '';

                            if (fieldName === 'national_code') {
                                fieldValue = maskNationalCode(fieldValue);
                                classes = 'dir-ltr text-left';
                            }
                            appendHtml('#entity-info', `
                                <span
                                    class="d-inline-block py-2 px-3 ms-2 rounded-2 ${variant} ${classes}"
                                    style="${borderStyle}"
                                >
                                    ${fieldValue}
                                </span>
                            `);
                        });
                    }

                    if (changeStatusInList === '1') {
                        $.each(relations, function (index, relation) {
                            let fieldValue = data[relation]?.name ?? '';
                            let colorClass = relationsColors[index] ?? 'text-bg-light-default';
                            let borderStyle = colorClassToBorderStyle(colorClass);

                            appendHtml('#entity-info', `
                                <span
                                    class="d-inline-block py-2 px-3 ms-2 rounded-2 ${colorClass}"
                                    style="${borderStyle}"
                                >
                                    ${fieldValue}
                                </span>
                            `);
                        });
                    }
                }).fail(() => {
                    toastr.error(errorMessage);
                }).always(() => {
                    resetButton.prop('disabled', false);
                    toggleLoading(form, false);
                    toggleButtonLoading(changeButton, false);
                });
            };

            $('.change-status-button').on('click', function () {
                setHtml('#entity-info', ' ');
                toggleLoading($('#entity-info'), true);

                const $btn = $(this);
                const entityId = $btn.data('entity-id');
                const entity = $btn.data('entity');
                const statusId = $btn.data('entity-status-id');
                const formAction = route("{{ $routeName }}", entityId);

                form.attr('action', formAction);
                toggleLoading(form);
                toggleButtonLoading(changeButton, true);
                resetButton.prop('disabled', true);

                $.post(statusURL, {
                    _token: csrfToken,
                    section: section,
                    status_id: statusId,
                    status_accesses_relation: statusAccessesRelation
                }).done(({data}) => {
                    showEntityDetail(entityId, entity);
                    $('#status-selector').getInstance().items(data.viewStatuses);
                }).fail(jqXHR => {
                    const msg = jqXHR.responseJSON?.message || errorMessage;
                    resetButton.prop('disabled', false);
                    toggleLoading(form, false);
                    toggleButtonLoading(changeButton, false);
                }).always(() => {
                    toggleLoading($('#entity-info'), false);
                });
            });
        </script>
    @endif
@endpush
