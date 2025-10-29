<x-default-layout>
    <div class="row mb-5">
        <x-card
            :title="__('basemodule::section.course_chapter_info')"
            class="mb-5"
            :no-padding="true"
        >
            <x-slot:headerActions>
                <x-button
                    type="link"
                    icon="fa-regular fa-pen-to-square fs-20"
                    variant="primary"
                    appearance="light"
                    size="xs"
                    :href="route('admin.courses.chapters.edit', ['course' => encryptValue($course->id), 'chapter' => encryptValue($chapter->id)])"
                />
            </x-slot:headerActions>

            <x-data-display
                bordered="items"
                :cols="2"
                :align-values="true"
            >
                <x-data-item
                    :value="$chapter->title"
                    span="full"
                    orientation="vertical"
                    class="m-3 fw-bold"
                />

                <x-data-item
                    :label="__('basemodule::field.status')"
                    :value="$chapter->is_active === \Dornica\Foundation\Core\Enums\IsActive::YES ? __('basemodule::enum.is_active.yes') : __('basemodule::enum.is_active.no')"
                    :badge="true"
                    :badgeVariant="$chapter->is_active === \Dornica\Foundation\Core\Enums\IsActive::YES ? 'success' : 'danger'"
                    badge-appearance="light"
                />

                <x-data-item
                    :label="__('basemodule::field.sort')"
                    :value="$chapter->sort"
                />

                <x-data-item
                    :label="__('basemodule::field.created_by')"
                >
                    {!! linkUserInfo(name: $course->createdBy->first_name.' '.$course->updatedBy->last_name, code: $course->createdBy->national_code) !!}
                </x-data-item>

                <x-data-item
                    :label="__('basemodule::field.updated_by')"
                >
                    {!! linkUserInfo(name: $course->updatedBy->first_name.' '.$course->updatedBy->last_name, code: $course->updatedBy->national_code) !!}
                </x-data-item>



                <x-data-item
                    :label="__('basemodule::field.created_at')"
                    :value="$chapter->created_at"
                    dir="ltr"
                />

                <x-data-item
                    :label="__('basemodule::field.updated_at')"
                    dir="ltr"
                >
                    <div class="d-flex align-items-center gap-2">
                        @canAccess('admin.api.v1.base-module.date-time.regenerate')
                        <i id="regenerateUpdatedAt" class="fa-light fa-refresh text-info fs-18 cursor-pointer"
                           onclick="regenerateUpdatedAt(this)"></i>
                        @endcanAccess
                        <div>{{ $chapter->updated_at }}</div>
                    </div>
                </x-data-item>
                <x-data-item
                    :label="__('basemodule::field.description')"
                    span="full"
                    orientation="vertical"
                >
                    {{ $chapter->description }}
                </x-data-item>
            </x-data-display>
        </x-card>
    </div>

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
                    confirmButtonText: '{{ __("basemodule::field.confirm") }}',
                    cancelButtonText: '{{ __("basemodule::field.cancel") }}',
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
                $.post("{{ route('admin.api.v1.base-module.date-time.regenerate') }}", {
                    _token: "{{ csrf_token() }}",
                    model: "{{ encryptValue($chapter->id) }}",
                    table: "course_chapters"
                })
                    .done(response => {
                        $icon.closest("div").find("div").html(response.data);
                        showToast("success", "{{ __('basemodule::message.change_successfully') }}");
                    })
                    .fail(() => {
                        showToast("error", "{{ __('basemodule::message.error_occurred') }}");
                    })
                    .always(stopRotation);
            }
        </script>
    @endpush

</x-default-layout>
