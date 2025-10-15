<div>
    @if($comment)
        @php
            $teacher = $comment->teacher ?? null;
            $admin = $comment->admin ?? null;
        @endphp

        <x-data-display
            bordered="all"
            class="mb-5"
            :cols="2"
        >
            <x-data-item
                :label="__('basemodule::field.user_general_info')"
                label-class="text-gray-700 fs-3"
                :show-colon="false"
                span="full"
                value=" "
            />

            <x-data-item
                :label="__('basemodule::field.sender')"
            >
                <x-link
                    href="#"
                    target="_blank"
                    variant="primary"
                >
                    @if($teacher)
                        {{ $teacher->full_name }}
                        <span class="d-inline-block dir-ltr text-left">
                                ({{ nationalCodeMaskFormatter($teacher->national_code) }})
                            </span>
                    @else
                        {{ $comment->name }}
                    @endif
                </x-link>
            </x-data-item>

            <x-data-item
                :label="'IP'"
                :value="$comment->ip ?? '-'"
            />

            <x-data-item
                :label="__('basemodule::field.email')"
                :value="$comment->email ?? '-'"
            />

            @if($comment->admin_reply)
                <x-data-item span="full" value-class="col-12">
                    <x-alert class="col-12 bg-gray-200 my-2">
                        <p class="fw-bold">{{ __('basemodule::field.admin_reply') }} :</p>
                        <p class="text-gray-700 text-justify">{{ $comment->admin_reply }}</p>
                    </x-alert>
                </x-data-item>
            @endif
        </x-data-display>

        <x-data-display
            bordered="items"
            bordered="all"
            :cols="2"
        >
            <x-data-item
                :label="__('basemodule::operation.show')"
                label-class="text-gray-700 fs-3"
                :show-colon="false"
                span="full"
                value=" "
            />
            <x-data-item
                :label="__('basemodule::field.comment_text')"
                span="full"
                orientation="vertical"
                valueClass="text-justify"
            >
                {!! $comment->comment !!}
            </x-data-item>

            <x-data-item
                :label="__('basemodule::field.send_time')"
                :value="$comment->created_at"
                dir="ltr"
            />

            <x-data-item
                :label="__('basemodule::field.comment_status')"
                :value="getEnumName(
                        enum: \Modules\BaseModule\Enums\General\ConfirmationStatus::class,
                            selected_item: $comment->status,
                            module: 'basemodule',
                            isEnum: false
                        )"
                :badge="true"
                :badge-variant="commentStatusVariant($comment->status->value)"
                badge-appearance="light"
            />

            <x-data-item
                :label="__('blog::general.fields.view_at')"
                :value="$comment->read_at ?? '-'"
                dir="ltr"
            />

            <x-data-item
                :label="__('basemodule::field.view_status')"
                :value="getEnumName(
                        enum: \Modules\BaseModule\Enums\General\IsRead::class,
                        selected_item: $comment->is_read,
                        module: 'basemodule',
                        isEnum: false
                    )"
                :badge="true"
                :badge-variant="$comment->is_read == \Modules\BaseModule\Enums\General\IsRead::YES ? 'success' : 'danger'"
                badge-appearance="light"
            />

            @if($comment->blog_id)
            <x-data-item
                :label="__('basemodule::field.like_count')"
                :value="$comment->likeCount()"
            />
            @endif

            @if($comment->blog_id)
            <x-data-item
                :label="__('basemodule::field.dislike_count')"
                :value="$comment->dislikeCount()"
            />
            @endif

            <x-data-item
                :label="__('basemodule::field.replied_at')"
                :value="$comment->replied_at ?? '-'"
                dir="ltr"
            />

            <x-data-item
                :label="__('basemodule::field.responder_admin')"
            >

                <x-link
                    href="#"
                    target="_blank"
                    variant="primary"
                >
                    @if($admin)
                        {{ $admin->full_name }}
                        <span class="d-inline-block dir-ltr text-left">
                                ({{ nationalCodeMaskFormatter($admin->national_code) }})
                            </span>
                    @else
                        -
                    @endif
                </x-link>
            </x-data-item>

            <x-data-item
                :label="__('basemodule::field.updated_at')"
                :value="$comment->updated_at ?? '-'"
                dir="ltr"
            />
        </x-data-display>
    @else
        <x-empty-state
            container-class="empty_component"
            :title="__('basemodule::message.loading_for_get_data')"
        />
    @endif
</div>

@push('scripts')
    <script>
        $(function () {
            toggleLoading('.empty_component');
        });
    </script>
@endpush
