

<x-default-layout>
    <x-card
        :title="__('bank::general.bank_info')"
        class="mb-5"
        :no-padding="true"
    >
{{--        @if(checkPolicy('canUpdate', $bank)->allowed())--}}
            <x-slot:headerActions>
                <x-button
                    type="link"
                    icon="fa-regular fa-pen-to-square fs-20"
                    variant="primary"
                    appearance="light"
                    size="xs"
                    type="link"
                    :href="route('admin.base-information.locations.edit', encryptValue($location->id))"
                />
            </x-slot:headerActions>
{{--        @endif--}}

        <x-data-display
            bordered="items"
            :cols="2"
            :align-values="true"
        >
            <x-data-item
                :label="__('location::general.branch')"
                :value="$location->branch"
            />
            <x-data-item
                :label="__('location::general.square')"
                :value="$location->square"
            />
            <x-data-item
                :label="__('location::general.street')"
                :value="$location->street"
            />

            <x-data-item
                :label="__('location::general.alley')"
                :value="$location->alley"
            />
            <x-data-item
                :label="__('location::general.service')"
                :badge="true"
                :badgeVariant="$location->service == \Modules\Location\Enums\Service::OFFLINE ? 'success' : 'danger'"
                value="{{$location->service == \Modules\Location\Enums\Service::OFFLINE ? __('location::enum.service.offline') : __('location::enum.service.online')}}"
            />
            <x-data-item
                :label="__('location::general.service')"
{{--                :badge="true"--}}
{{--                :badgeVariant="$location->service == \Modules\Location\Enums\Service::OFFLINE ? 'success' : 'danger'"--}}
{{--                value="{{$location->service == \Modules\Location\Enums\Service::OFFLINE ? __('location::enum.service.offline') : __('location::enum.service.online')}}"--}}
            >
                <x-badge
                    value="فعال"
                    icon="fas fa-check"
                    size="lg"
                    :pill="true"
                    variant="info"
                />
            </x-data-item>



            <x-data-item
                :label="__('location::general.is_active')"
                :badge="true"
                :badgeVariant="$location->is_active == \Dornica\Foundation\Core\Enums\IsActive::YES ? 'success' : 'danger'"
                value="{{$location->is_active == \Dornica\Foundation\Core\Enums\IsActive::YES ? __('location::enum.is_active.yes') : __('location::enum.is_active.no')}}"
            />

            <x-data-item
                :label="__('location::general.created_at')"
                :value="$location->created_at"
                dir="ltr"
            />

            <x-data-item
                :label="__('location::general.updated_at')"
                :value="$location->updated_at"
                dir="ltr"
            />

            <x-data-item
                :label="__('location::general.full_address')"
                :value="$location->full_address"
            />

            <x-data-item
                :label="__('location::general.color')"
                :badge="true"
                :badge-custom-color="$location->color"
                style="display:inline-block; width:30px; height:30px; border-radius:50%; background-color:{{ $location->color }}; border:1px solid #ccc;"
                class="rounded circle-custom"
            >
            </x-data-item>


            <x-data-item
                :label="__('location::general.description')"
                span="full"
                orientation="vertical"
            >
                {{ $location->description }}
            </x-data-item>

            <x-data-item
                :label="__('location::general.location_image')"
                :span="2"
                orientation="vertical"
            >
                <x-image
                    :src="$location->avatar_id"
                    :alt="$location->title"
                    :radius="4"
                    :showPopup="true"
                    :showIcon="true"
                    :width="250"
                    :height="150"
                />
            </x-data-item>
        </x-data-display>
{{--            <x-mapbox--}}
{{--                :zoom="9"--}}
{{--                height="400px"--}}
{{--                :center-latitude="36.568058"--}}
{{--                :center-longitude="52.850499"--}}
{{--                :interactive="true"--}}
{{--                :searchable="true"--}}
{{--                :drawable="true"--}}
{{--                :controls="['navigation', 'fullscreen', 'geolocation']"--}}
{{--                :markers="[--}}
{{--        [--}}
{{--            'latitude' => 36.5559807,--}}
{{--            'longitude' => 53.0512492,--}}
{{--            'title' => 'ساری',--}}
{{--            'color' => 'var(--bs-success)',--}}
{{--        ],--}}
{{--        [--}}
{{--            'latitude' => 36.5406547,--}}
{{--            'longitude' => 52.6743405,--}}
{{--            'title' => 'بابل',--}}
{{--            'color' => '#563d2d',--}}
{{--        ],--}}
{{--        [--}}
{{--            'latitude' => 36.459708,--}}
{{--            'title' => 'قائمشهر',--}}
{{--            'longitude' => 52.848358,--}}
{{--        ],--}}
{{--    ]"--}}
{{--            />--}}
    </x-card>

    {{--    <x-card--}}
    {{--        :title="__('bank::general.section_stats')"--}}
    {{--        class="mb-5"--}}
    {{--    >--}}
    {{--        <div class="row g-4">--}}
    {{--            <x-stat-card--}}
    {{--                :label="__('bank::general.views_count')"--}}
    {{--                :value="numberFormatter($viewStatistics['this_month']['count'])"--}}
    {{--                value-class="fs-30 fw-600"--}}
    {{--                labelIconClass="bg-light-primary"--}}
    {{--                labelIcon="fa-regular fa-eye text-primary p-4"--}}
    {{--                container-class="col-xl-3 col-md-6 col-12 h-100"--}}
    {{--                class="h-100"--}}
    {{--            >--}}
    {{--                <x-slot:suffix>--}}
    {{--                    <x-badge--}}
    {{--                        appearance="light"--}}
    {{--                        :variant="$viewStatistics['this_month']['variant']"--}}
    {{--                        value="{{ $viewStatistics['this_month']['percent'] }}%"--}}
    {{--                        size="xs"--}}
    {{--                        :class="$viewStatistics['this_month']['icon'] ? 'd-flex' : 'd-none'"--}}
    {{--                        icon="fa-regular {{ $viewStatistics['this_month']['icon'] }}"--}}
    {{--                    />--}}
    {{--                </x-slot>--}}
    {{--            </x-stat-card>--}}

    {{--            <x-stat-card--}}
    {{--                :label="__('bank::general.comments_count')"--}}
    {{--                :value="numberFormatter($commentStatistics['this_month']['count'])"--}}
    {{--                value-class="fs-30 fw-600"--}}
    {{--                labelIconClass="bg-light-danger"--}}
    {{--                labelIcon="fa-regular fa-messages fs-16 text-danger p-4"--}}
    {{--                container-class="col-xl-3 col-md-6 col-12 h-100"--}}
    {{--                class="h-100"--}}
    {{--            >--}}
    {{--                <x-slot:suffix>--}}
    {{--                    <x-badge--}}
    {{--                        appearance="light"--}}
    {{--                        :variant="$commentStatistics['this_month']['variant']"--}}
    {{--                        value="{{ $commentStatistics['this_month']['percent'] }}%"--}}
    {{--                        size="xs"--}}
    {{--                        :class="$commentStatistics['this_month']['icon'] ? 'd-flex' : 'd-none'"--}}
    {{--                        icon="fa-regular {{ $commentStatistics['this_month']['icon'] }}"--}}
    {{--                    />--}}
    {{--                </x-slot>--}}
    {{--            </x-stat-card>--}}

    {{--            <x-stat-card--}}
    {{--                :label="__('bank::general.followers_count')"--}}
    {{--                :value="numberFormatter($favoriteStatistics['this_month']['count'])"--}}
    {{--                value-class="fs-30 fw-600"--}}
    {{--                labelIconClass="bg-light-warning"--}}
    {{--                labelIcon="fa-regular fa-users text-warning p-4"--}}
    {{--                container-class="col-xl-3 col-md-6 col-12 h-100"--}}
    {{--                class="h-100"--}}
    {{--            >--}}
    {{--                <x-slot:suffix>--}}
    {{--                    <x-badge--}}
    {{--                        appearance="light"--}}
    {{--                        :variant="$favoriteStatistics['this_month']['variant']"--}}
    {{--                        value="{{ $favoriteStatistics['this_month']['percent'] }}%"--}}
    {{--                        size="xs"--}}
    {{--                        :class="$favoriteStatistics['this_month']['icon'] ? 'd-flex' : 'd-none'"--}}
    {{--                        icon="fa-regular {{ $favoriteStatistics['this_month']['icon'] }}"--}}
    {{--                    />--}}
    {{--                </x-slot>--}}
    {{--            </x-stat-card>--}}

    {{--            <x-stat-card--}}
    {{--                :label="__('bank::general.reactions_count')"--}}
    {{--                :value="numberFormatter($reactionStatistics['this_month']['count'])"--}}
    {{--                value-class="fs-30 fw-600"--}}
    {{--                labelIconClass="bg-light-info-2"--}}
    {{--                labelIcon="fa-regular fa-message-heart text-info-2 p-4"--}}
    {{--                container-class="col-xl-3 col-md-6 col-12 h-100"--}}
    {{--                class="h-100"--}}
    {{--            >--}}
    {{--                <x-slot:suffix>--}}
    {{--                    <x-badge--}}
    {{--                        appearance="light"--}}
    {{--                        :variant="$reactionStatistics['this_month']['variant']"--}}
    {{--                        value="{{ $reactionStatistics['this_month']['percent'] }}%"--}}
    {{--                        size="xs"--}}
    {{--                        :class="$reactionStatistics['this_month']['icon'] ? 'd-flex' : 'd-none'"--}}
    {{--                        icon="fa-regular {{ $reactionStatistics['this_month']['icon'] }}"--}}
    {{--                    />--}}
    {{--                </x-slot>--}}
    {{--            </x-stat-card>--}}
    {{--        </div>--}}
    {{--    </x-card>--}}

    {{--  Change Status Component  --}}
    {{--    <x-change-status--}}
    {{--        :route-name="'admin.books.change-status'"--}}
    {{--        section-name-in-workflow="book"--}}
    {{--        :modal-badges="[--}}
    {{--            ['value' => $book->code, 'variant' => 'info'],--}}
    {{--            ['value' => $book->title, 'variant' => 'success'],--}}
    {{--            ['value' => $book->educationalGroup->name, 'variant' => 'info-2'],--}}
    {{--            ['value' => $book->educationalGrade->name, 'variant' => 'warning']--}}
    {{--        ]"--}}
    {{--        :model-id="encryptValue($book->id)"--}}
    {{--        :status-id="encryptValue($book->book_status_id)"--}}
    {{--        parameter-name="book"--}}
    {{--        status-accesses-relation="bookStatusAccesses"--}}
    {{--        selectBoxName="book_status_id"--}}
    {{--        :status-model="\Modules\BookStatus\Models\BookStatus::class"--}}
    {{--        :form-request="\Modules\Book\Http\Requests\Book\BookChangeStatusRequest::class"--}}
    {{--    />--}}

    @push("scripts")
        <script>
            function regenerateUpdatedAt(tag) {
                const $icon = $(tag);
                startRotation($icon);

                Swal.fire({
                    html: "{{ __('basemodule::message.change_confirmation') }}",
                    icon: "info",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText:"ok",
                    cancelButtonText:"cancel",
                    customClass: {
                        confirmButton: "btn btn-info",
                        cancelButton: 'btn btn-danger'
                    }
                }).then(result => {
                    result.isDismissed ? stopRotation() : sendRegenerateRequest($icon);
                });
            }

            function startRotation($icon) {
                let angle = 0;
                stopRotation(); // Prevent duplicate intervals
                window.rotationInterval = setInterval(() => {
                    angle = (angle + 10) % 360;
                    $icon.css('transform', `rotate(${angle}deg)`);
                }, 50);
            }

            function stopRotation() {
                if (window.rotationInterval) {
                    clearInterval(window.rotationInterval);
                    window.rotationInterval = null;
                }
            }

            function sendRegenerateRequest($icon) {
                $.post("{{ route('admin.api.v1.banks.date-time.regenerate') }}", {
                    _token: "{{ csrf_token() }}",
                    model: "{{ encryptValue($location->id) }}",
                    table: "banks"
                })
                    .done(response => {
                        $icon.closest("div").find("div").html(response.data);
                        showToast("success", "تغییرات با موفقیت انجام شد");
                    })
                    .fail(() => {
                        showToast("error", "تغییرات با خطا مواجه شد");
                    })
                    .always(stopRotation);
            }
        </script>
    @endpush
</x-default-layout>

