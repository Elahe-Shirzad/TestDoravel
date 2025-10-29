<x-default-layout>
    <x-card
        :title="__('course::section.course_detail')"
        class="mb-5"
        content-class="p-0"
    >
        @if(checkPolicy('canUpdate', $course)->allowed())
            <x-slot:headerActions>
                <x-button
                    type="link"
                    icon="fa-regular fa-pen-to-square fs-20"
                    variant="primary"
                    appearance="light"
                    size="xs"
                    :href="route('admin.courses.edit', ['course' => encryptValue($course->id)])"
                />
            </x-slot:headerActions>
        @endif

        <x-data-display bordered="items" :cols="2">

            <x-data-item :span="2" value-class="d-flex gap-3 align-items-center flex-wrap">
                <x-image
                    :src="$course->image_id"
                    :fallbackSrc="asset('assets/image/no-pic.jpg')"
                    :alt="$course->title"
                    :radius="4"
                    :showPopup="true"
                    :showIcon="true"
                    :width="95"
                    :height="65"
                />
                <div class="d-flex flex-column flex-grow-1 gap-2">

                    <div class="d-flex gap-1 align-items-center flex-wrap">
                        <span class="text-gray-700 fs-14">{{$course->title}}</span>
                    </div>
                </div>
            </x-data-item>

            <x-data-item
                :label="__('basemodule::field.category')"
                :value="$course->courseCategory->title ?? '-'"
                :badge="true"
                badge-custom-color="#78829d"
                badge-appearance="light"
            />

            <x-data-item
                :label="__('course::field.level')"
                :value="$course->courseLevel->title ?? '-'"
            />

            <x-data-item
                label="زمان شروع دوره"
                :value="($course->started_at) ?? '-'"
                dir="ltr"
            />

            <x-data-item
                label="زمان پایان دوره"
                :value="($course->end_at) ?? '-'"
                dir="ltr"
            />

            @php
                $userTypeIcon = "";
                $userTypeBadgeVariant = "secondary";
                if($course->user_type == \Modules\BaseModule\Enums\General\UserType::USERS) {
                    $userTypeIcon = "fa-light fa-users";
                    $userTypeBadgeVariant = "info";
                }
                if($course->user_type == \Modules\BaseModule\Enums\General\UserType::ALL) {
                    $userTypeIcon = "fa-light fa-users";
                    $userTypeBadgeVariant = "success";
                }
            @endphp

            <x-data-item
                :label="__('basemodule::field.user_type')"
                :value="getEnumName(\Modules\BaseModule\Enums\General\UserType::class, $course->user_type, 'basemodule')"
                :badge="true"
                :badgeIcon="$userTypeIcon"
                :badge-variant="$userTypeBadgeVariant"
                badge-appearance="light"
                badgeIconPosition="right"
            />


            <x-data-item
                :label="__('basemodule::field.created_by')"
            >
                {!! linkUserInfo(name: $course->createdBy->first_name, code: $course->createdBy->national_code) !!}
            </x-data-item>

            <x-data-item
                :label="__('basemodule::field.updated_by')"
            >
                {!! linkUserInfo(name: $course->updatedBy->first_name, code: $course->updatedBy->national_code) !!}
            </x-data-item>

            <x-data-item
                :label="__('basemodule::field.created_at')"
                :value="$course->created_at"
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
                    <div>{{ $course->updated_at }}</div>
                </div>
            </x-data-item>

            <x-data-item
                :label="__('basemodule::field.status')"
                :value="$course->courseStatus->name ?? '-'"
                :badge="true"
                :badge-custom-color="$course->courseStatus->color"
                badge-appearance="light"
            />

            <x-data-item
                :label="__('course::field.course_type')"
                :value="getEnumName(\Modules\BaseModule\Enums\General\BooleanState::class, $course->is_special, 'course')"
                :badge="true"
                badge-appearance="light"
            />

            <x-data-item
                label="نام مدرس"
                :value="$course->instructor->full_name?? '-'"
                :badge="true"
                badge-custom-color="#78829d"
                badge-appearance="light"
                badgeIcon="fa-regular fa-user"
                badgeIconPosition="right"
            />

            <x-data-item
                :label="__('basemodule::field.small_description')"
                :value="$course->small_description ?? '-'"
                span="full"
                orientation="vertical"
            />


            <x-data-item
                :label="__('basemodule::field.image')"
                span="full"
                orientation="vertical"
            >
                <x-image
                    :src="$course->image_id"
                    :fallbackSrc="asset('assets/image/no-pic.jpg')"
                    :alt="$course->title"
                    :radius="4"
                    :showPopup="true"
                    :showIcon="true"
                    :width="300"
                    :height="200"
                />
            </x-data-item>

        </x-data-display>
    </x-card>



    {{--  Change Status Component  --}}
    <x-change-status
        :route-name="'admin.courses.change-status'"
        section-name-in-workflow="course"
        :modal-badges="[
            ['value' => $course->id, 'variant' => 'info'],
            ['value' => $course->title, 'variant' => 'success']
        ]"
        :model-id="encryptValue($course->id)"
        :status-id="encryptValue($course->course_status_id)"
        parameter-name="course"
        status-accesses-relation="courseStatusAccesses"
        selectBoxName="course_status_id"
        :status-model="\Modules\CourseStatus\Models\CourseStatus::class"
        :form-request="\Modules\Course\Http\Requests\ChangeCourseStatusRequest::class"
    />


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
                    model: "{{ encryptValue($course->id) }}",
                    table: "courses"
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
