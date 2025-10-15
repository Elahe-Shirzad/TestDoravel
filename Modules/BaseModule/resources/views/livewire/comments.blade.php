@push('styles')
    <style>
        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }

        .search-wrapper input {
            padding-right: 2rem;
        }
    </style>
@endpush

<x-card :title="__('basemodule::section.comments_sent_list')" class="mb-5">
    <x-slot:headerActions>
        <div class="d-flex gap-2 align-items-center justify-content-between w-100 flex-wrap">

            {{--  Search Input  --}}
            <div class="search-wrapper">
                <i class="fa-regular fa-magnifying-glass"></i>
                <input
                    type="text"
                    wire:model.live.debounce.1000ms="search"
                    placeholder="{{ __('basemodule::operation.search_with_dots') }}"
                    class="form-control"
                />
            </div>

            <x-dropdown-button
                :title="__('basemodule::operation.bulk_operation')"
                icon="fas fa-chevron-down"
                variant="dark"
                style="min-width: 150px; justify-content: space-between;"
                :closeOnClick="true"
            >
                <x-dropdown-item
                    id="bulk_delete"
                    :title="__('basemodule::operation.destroy_selected_items')"
                    {{--  wire:click="bulkDelete"  --}}
                    :confirmation="true"
                    :confirmationMessage="__('basemodule::message.delete_confirmation_message')"
                    confirmationIcon="fa-regular fa-trash-can"
                    confirmationType="danger"
                    variant="danger"
                    elementClass="x-table-bulk-operation-button-item text-danger"
                    :confirmButtonText="__('basemodule::operation.destroy')"
                />
            </x-dropdown-button>
        </div>
    </x-slot:headerActions>

    <div class="row row-cols-1 row-cols-md-6 row-cols-lg-4 row-cols-xl-5 g-4">
        <x-stat-card
            class="border-secondary"
            :label="__('basemodule::field.all')"
            label-icon="fa-light fa-messages"
            :value="numberFormatter($entityInfo->$entityCommentRelation()->count())"
            variant="secondary"
            container-class="col"
        />
        <x-stat-card
            :label="__('basemodule::field.not_read')"
            :value="numberFormatter($entityInfo->$entityCommentRelation()->where('is_read', \Modules\BaseModule\Enums\General\IsRead::NO)->count())"
            variant="warning"
            label-icon="fa-regular fa-message"
            container-class="col"
        />
        <x-stat-card
            :label="__('basemodule::field.confirmed')"
            :value="numberFormatter($entityInfo->$entityCommentRelation()->where('status', \Modules\BaseModule\Enums\General\ConfirmationStatus::CONFIRMED)->count())"
            variant="success"
            labelIcon="fa-regular fa-check-double"
            container-class="col"
        />
        <x-stat-card
            :label="__('basemodule::field.not_confirmed')"
            :value="numberFormatter($entityInfo->$entityCommentRelation()->where('status', \Modules\BaseModule\Enums\General\ConfirmationStatus::NOT_CONFIRMED)->count())"
            variant="danger"
            label-icon="fa-regular fa-xmark-large"
            container-class="col"
        />
        <x-stat-card
            :label="__('basemodule::field.processing')"
            :value="numberFormatter($entityInfo->$entityCommentRelation()->where('status', \Modules\BaseModule\Enums\General\ConfirmationStatus::PROCESSING)->count())"
            variant="info"
            labelIcon="fa-regular fa-message-question"
            container-class="col"
        />
    </div>

    <div class="row mt-5" id="comments_section">
        @if($comments && $comments->count())
            @forelse($comments as $comment)
                @php
                    $commentId = encryptValue($comment->id);
                    $isReadBadgeVariant = $comment->is_read == \Modules\BaseModule\Enums\General\IsRead::YES ? 'success' : 'danger';
                    $teacher = $comment->teacher;
                    $teacherName = $teacher ? $teacher->full_name : $comment->name;
                    $relatedCommentsCount = $comment->$entityCommentRelation()->count();

                    // Delete Params Section
                    $sectionIdName = $section . '_id';
                    $secondParam = $section . '_comment';
                    $deleteParameters = [$section => encryptValue($comment->$sectionIdName), $secondParam => $commentId];
                    $deleteConfirmationMessage = $relatedCommentsCount ? __('basemodule::message.delete_confirmation_message_with_dependencies', ['count' => $relatedCommentsCount]) : __('basemodule::message.delete_confirmation_message');
                @endphp

                <div class="d-flex align-items-center">
                    <div class="checkbox-cls">
                        <x-checkbox
                            wire:model="selectedComments"
                            name="selectedComments"
                            :value="$commentId"
                            containerClass="x-table-bulk-operation-item"
                            class="cursor-pointer"
                        />
                    </div>

                    <div class="data-row-cls flex-grow-1">
                        <x-data-row
                            :title="$teacherName"
                            class="mb-3"
                        >
                            <x-slot:titleSuffix>
                                @if($teacher)
                                    <span class="d-inline-block dir-ltr text-left">
                                        ({{ nationalCodeMaskFormatter($teacher->national_code) }})
                                    </span>
                                @endif

                                <x-badge
                                    :variant="$isReadBadgeVariant"
                                    :value="getEnumName(\Modules\BaseModule\Enums\General\IsRead::class, $comment->is_read, 'basemodule')"
                                    appearance="light"
                                    size="sm"
                                />

                                <x-badge
                                    :variant="commentStatusVariant($comment->status->value)"
                                    :value="getEnumName(\Modules\BaseModule\Enums\General\ConfirmationStatus::class, $comment->status, 'basemodule')"
                                    appearance="light"
                                    size="sm"
                                />

                                @if($comment->member_type)
                                    <x-badge
                                        :variant="$comment->member_type === \Modules\Page\Enums\MemberType::TEACHER ? 'success' : 'warning'"
                                        :value="getEnumName(\Modules\Page\Enums\MemberType::class, $comment->member_type, 'page')"
                                        appearance="light"
                                        size="sm"
                                    />
                                @endif
                            </x-slot:titleSuffix>

                            <x-slot:prefix>
                                <x-image
                                    :src="$teacher?->avatar_id"
                                    :fallbackSrc="asset('assets/image/avatar.png')"
                                    :alt="$teacherName"
                                    radius="full"
                                    :width="50"
                                    :height="50"
                                />
                            </x-slot>

                            <x-slot:subtitle>
                                <x-data-item
                                    :label="__('basemodule::field.send_time')"
                                    :value="$comment->created_at"
                                    dir="ltr"
                                />

                                @if($comment->blog_id || $comment->course_id)
                                    <x-data-item
                                        :label="__('basemodule::field.like_count')"
                                        :value="$comment->likeCount()"
                                    />
                                @endif

                                @if($comment->blog_id || $comment->course_id)
                                    <x-data-item
                                        :label="__('basemodule::field.dislike_count')"
                                        :value="$comment->dislikeCount()"
                                    />
                                @endif
                            </x-slot:subtitle>

                            <x-slot:actions>
                                @if($relatedCommentsCount)
                                    <x-button
                                        :title="__('basemodule::section.related_comments')"
                                        size="sm"
                                        appearance="outline"
                                        variant="light"
                                        wire:click="showRelatedComments('{{ $commentId }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="showRelatedComments('{{ $commentId }}')"
                                    />
                                @endif
                                <x-dropdown-button
                                    variant="primary"
                                    appearance="light"
                                    :title="__('basemodule::operation.actions')"
                                    icon="fa-regular fa-ellipsis-vertical"
                                    :closeOnClick="false"
                                    position="bottom"
                                    menuPosition="right"
                                    size="sm"
                                >
                                    <x-dropdown-item
                                        :title="__('basemodule::operation.show')"
                                        element-class="detail_button"
                                        wire:click="showComment('{{ $commentId }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="showComment('{{ $commentId }}')"
                                    />

                                    <x-dropdown-item
                                        :title="__('basemodule::operation.change_status')"
                                        element-class="change_status_button"
                                        wire:click="showCommentChangeStatus('{{ $commentId }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="showCommentChangeStatus('{{ $commentId }}')"
                                    />

                                    <x-dropdown-item
                                        :title="__('basemodule::operation.destroy')"
                                        element-class="text-danger"
                                        variant="danger"
                                        :href="route($deleteRouteName, $deleteParameters)"
                                        :confirmation="true"
                                        :confirmation-message="$deleteConfirmationMessage"
                                        confirmation-icon="fa-regular fa-trash-can"
                                        confirmation-type="danger"
                                        method="DELETE"
                                        :confirm-button-text="__('basemodule::operation.destroy')"
                                    />
                                </x-dropdown-button>
                            </x-slot:actions>

                            <x-data-item
                                :label="__('basemodule::field.comment_text')"
                                valueClass="text-justify"
                            >
                                {!! $comment->comment !!}
                            </x-data-item>
                        </x-data-row>
                    </div>
                </div>
            @empty
                <x-empty-state :title="__('basemodule::message.no_info_exists')"/>
            @endforelse

            @if ($comments->hasPages())
                <div class="x-table-pagination d-flex justify-content-center gap-3 align-items-center mt-3 mb-5">
                    {{ $comments->links('table-generator::pagination') }}
                </div>
            @endif
        @else
            <x-empty-state
                :title="__('basemodule::message.no_info_exists')"
            />
        @endif
    </div>

    <x-card
        class="mb-5"
        :title="__('basemodule::section.comment_sent_statistics')"
    >
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 mb-4">
            @foreach(['today', 'this_week', 'this_month', 'this_year', 'all'] as $key)
                <div class="col">
                    <x-stat-card
                        :label="__('basemodule::field.' . $key)"
                        :value="numberFormatter($statistics[$key]['count'])"
                    >
                        @if($key !== 'all')
                            <x-slot:suffix>
                                <x-badge
                                    :variant="$statistics[$key]['variant']"
                                    value="{{ $statistics[$key]['percent'] }}%"
                                    appearance="light"
                                    size="xs"
                                    icon-position="left"
                                    :class="$statistics[$key]['icon'] ? 'd-flex' : 'd-none'"
                                    icon="fa-regular {{ $statistics[$key]['icon'] }}"
                                />
                            </x-slot:suffix>
                        @endif
                    </x-stat-card>
                </div>
            @endforeach
        </div>

        <x-card
            class="mb-5"
            :title="__('basemodule::section.comment_sent_statistics_chart')"
            title-class="text-gray-700 fw-400"
            :expanded="true"
            :default-expanded="false"
        >
            <x-high-chart
                :options="[
                'legend' => [
                        'itemDistance' => 20,
                    ]
                ]"
                :categories="[
                    [
                        'active' => true,
                        'title' => __('basemodule::field.charts_labels.week'),
                        'options' => [
                            'xAxis' => [
                                'categories' => $chartData['week']['persianLabels'] ?? [],
                            ]
                        ],
                        'series' => [
                            [
                                'type' => 'spline',
                                'name' => __('basemodule::field.all_comment'),
                                'data' => $chartData['week']['counts']['allComments'] ?? [],
                                'color' => '#6c757d',
                            ],
                            [
                                'type' => 'column',
                                'name' => __('basemodule::field.answered'),
                                'data' => $chartData['week']['counts']['readComments'] ?? [],
                                'color' => '#22C55E',
                            ]
                        ],
                    ],
                    [
                        'title' => __('basemodule::field.charts_labels.month'),
                        'options' => [
                            'xAxis' => [
                                'categories' => $chartData['month']['persianLabels'] ?? [],
                            ]
                        ],
                        'series' => [
                            [
                                'type' => 'spline',
                                'name' => __('basemodule::field.all_comment'),
                                'data' => $chartData['month']['counts']['allComments'] ?? [],
                                'color' => '#6c757d',
                            ],
                            [
                                'type' => 'column',
                                'name' => __('basemodule::field.answered'),
                                'data' => $chartData['month']['counts']['readComments'] ?? [],
                                'color' => '#22C55E',
                            ]
                        ],
                    ],
                    [
                        'title' => __('basemodule::field.charts_labels.year'),
                        'options' => [
                            'xAxis' => [
                                'categories' => $chartData['year']['persianLabels'] ?? [],
                            ]
                        ],
                        'series' => [
                            [
                                'type' => 'spline',
                                'name' => __('basemodule::field.all_comment'),
                                'data' => $chartData['year']['counts']['allComments'] ?? [],
                                'color' => '#6c757d',
                            ],
                            [
                                'type' => 'column',
                                'name' => __('basemodule::field.answered'),
                                'data' => $chartData['year']['counts']['readComments'] ?? [],
                                'color' => '#22C55E',
                            ]
                        ],
                    ],
                    [
                        'title' => __('basemodule::field.charts_labels.all'),
                        'options' => [
                            'xAxis' => [
                                'categories' => $chartData['all']['persianLabels'] ?? [],
                            ]
                        ],
                        'series' => [
                            [
                                'type' => 'spline',
                                'name' => __('basemodule::field.all_comment'),
                                'data' => $chartData['all']['counts']['allComments'] ?? [],
                                'color' => '#6c757d',
                            ],
                            [
                                'type' => 'column',
                                'name' => __('basemodule::field.answered'),
                                'data' => $chartData['all']['counts']['readComments'] ?? [],
                                'color' => '#22C55E',
                            ]
                        ],
                    ],
                ]"
            />
        </x-card>
    </x-card>

    {{--  TODO:: if it doesn't exist modal styles not load  --}}
    <x-data-display
        bordered="all"
        class="mb-5 d-none"
        :cols="2"
    >
    </x-data-display>

    {{-- Comment Detail Modal --}}
    <div id="comment-detail-wrapper" wire:ignore>
        <x-modal
            :title="__('basemodule::section.comment_detail')"
            size="lg"
            id="showCommentModal"
            :closable="false"
        >
            <livewire:comment-detail
                :comment-id="$selectedComment"
                :model="$commentModel"
                wire:id="commentDetailComponent"
            />
        </x-modal>
    </div>

    {{-- Related Comment Modal --}}
    <div id="related-comment-wrapper">
        <x-modal
            :title="__('basemodule::section.related_comments')"
            size="lg"
            id="showRelatedCommentsModal"
            :closable="false"
        >
            {{--  TODO:: can't controll badge count in out of component even with js  --}}
            {{--            <x-slot:title_badges>--}}
            {{--                <x-badge--}}
            {{--                    :value="__('basemodule::general.comments_count') . ': 0'"--}}
            {{--                    variant="info"--}}
            {{--                    appearance="light"--}}
            {{--                    class="total_count_badge"--}}
            {{--                />--}}
            {{--            </x-slot:title_badges>--}}
            <livewire:related-comment
                :related-comment-id="$relatedCommentId"
                :model="$commentModel"
                per-page="5"
                wire:id="relatedCommentComponent"
            />
        </x-modal>
    </div>

    {{-- Change Comment Status  Modal --}}
    <div id="comment-change-status-wrapper">
        <x-modal
            :title="__('basemodule::operation.change_status')"
            size="lg"
            id="showCommentChangeStatusModal"
            on-close-action="clear"
            :closable="false"
        >
            <livewire:comment-change-status
                :comment-id="$selectedComment"
                :model="$commentModel"
                wire:id="commentChangeStatusComponent"
            />

            <x-slot:footer>
                <div class="pb-0 px-0 d-flex gap-4 justify-content-end">
                    <div class="d-flex gap-4 justify-content-end">
                        <x-reset-button
                            :title="__('basemodule::operation.reset')"
                            type="button"
                            formId="comment_change_status"
                            variant="light"
                            appearance="outline"
                        />

                        <x-button
                            :title="__('basemodule::operation.submit')"
                            type="button"
                            formId="comment_change_status"
                            button-type="submit"
                            class="btn_submit"
                            id="change_status_button"
                        />
                    </div>
                </div>
            </x-slot:footer>
        </x-modal>
    </div>
</x-card>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-comment-detail-modal', (event) => {
                const wrapper = document.querySelector('#comment-detail-wrapper');
                const componentEl = wrapper?.querySelector('[wire\\:id]');
                let componentId = componentEl.getAttribute('wire:id');
                const component = Livewire.find(componentId);
                component.call('loadComment', event[0].model, event[0].commentId);

                const modalId = event[0].id;
                const modal = document.getElementById(modalId);
                if (modal) {
                    setTimeout(() => {
                        const bsModal = new bootstrap.Modal(modal);
                        bsModal.show();
                    }, 1000);
                }
            });
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-related-comment-modal', (event) => {
                const wrapper = document.querySelector('#related-comment-wrapper');
                const componentEl = wrapper?.querySelector('[wire\\:id]');
                let componentId = componentEl.getAttribute('wire:id');
                const component = Livewire.find(componentId);
                component.call('loadComment', event[0].model, event[0].relatedCommentId);

                const modalId = event[0].id;
                const modal = document.getElementById(modalId);
                if (modal) {
                    setTimeout(() => {
                        const bsModal = new bootstrap.Modal(modal);
                        bsModal.show();

                        // Refresh main component when modal is closed
                        modal.addEventListener('hidden.bs.modal', function refreshOnClose() {
                            Livewire.dispatch('refreshComments');
                            // Remove listener to prevent multiple triggers
                            modal.removeEventListener('hidden.bs.modal', refreshOnClose);
                        }, {once: true});
                    }, 1000);
                }
            });
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-comment-change-status-modal', (event) => {
                const wrapper = document.querySelector('#comment-change-status-wrapper');
                const componentEl = wrapper?.querySelector('[wire\\:id]');
                let componentId = componentEl.getAttribute('wire:id');
                const component = Livewire.find(componentId);
                component.call('loadComment', event[0].model, event[0].commentId);

                const modalId = event[0].id;
                const modal = document.getElementById(modalId);
                if (modal) {
                    setTimeout(() => {
                        const bsModal = new bootstrap.Modal(modal);
                        bsModal.show();
                    }, 1000);
                }
            });
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-change-status-toast', () => {
                showToast(
                    "success",
                    "{{ __('basemodule::message.change_status_successfully') }}",
                    "{{ __('basemodule::field.success') }}"
                );
                location.reload();
            });
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-empty-bulk-delete-items-toast', () => {
                showToast(
                    "error",
                    "{{ __('panel-kit::table-generator.bulk-operations.no-exist-selected-item') }}",
                    "{{ __('basemodule::field.error') }}"
                );
            });
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-bulk-delete-successfully-toast', () => {
                showToast(
                    "success",
                    "{{ __('basemodule::message.delete_successfully') }}",
                    "{{ __('basemodule::field.success') }}"
                );
            });
        });

        {{--  TODO:: if one modal of related-comment, change-status, comment detail opened, can't open modal for bulk or delete  --}}
        $(document).on('show.bs.modal', function (event) {
            const triggerElement = $(event.relatedTarget);
            if (triggerElement.hasClass('x-table-bulk-operation-button-item')) {
                const itemInputElement = $(".x-table-bulk-operation-item input:checked");
                if (itemInputElement.length === 0) {
                    event.preventDefault();
                    return false;
                }
            }
        });

        function doOperation(element, selectedItems) {
            @this.
            call('bulkDelete');
        }

        $(".x-table-bulk-operation-button-item").on("click", function (e) {
            const element = $(this);
            let itemInputElement = $(".x-table-bulk-operation-item input:checked");

            if (itemInputElement.length === 0) {
                showToast("error", '{{ __('panel-kit::table-generator.bulk-operations.no-exist-selected-item') }}');
                e.stopImmediatePropagation()
                return;
            }

            let selectedItems = {
                ids: []
            };

            $.each(itemInputElement, function (index, item) {
                selectedItems.ids.push($(item).parents("tr").data("id"));
            });

            const hasConfirmation =
                element.attr("data-bs-toggle") === "confirmation-modal" &&
                element.attr("data-bs-target") !== undefined;

            if (!hasConfirmation) {
                doOperation(element, selectedItems);
                return;
            }

            const confirmationModalClass = element.attr('data-bs-target').slice(1);
            setTimeout(() => {
                $(`.${confirmationModalClass} .btn-confirm-modal`).on('click', function () {
                    doOperation(element, selectedItems);
                })
            }, 100);
        });
    </script>
@endPush
