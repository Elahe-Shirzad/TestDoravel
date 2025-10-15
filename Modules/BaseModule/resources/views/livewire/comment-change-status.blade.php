<div
    id="commentChangeStatusSection"
    class="row"
>
    @if ($commentId)
        @php
            $comment = $model::find($commentId);
            $teacherName = $comment->teacher ? $comment->teacher->full_name : $comment->name;
            $teacherNationalCode = $comment->teacher ? nationalCodeMaskFormatter($comment->teacher->national_code) : null;
            $teacherImage = $comment->teacher ? $comment->teacher->avatar_id : null;
        @endphp

        <x-data-row
            :title="$teacherName"
            class="border-primary bg-gray-200"
        >
            @if($teacherNationalCode)
                <x-slot:titleSuffix>
                    <span class="d-inline-block dir-ltr text-left">
                        ({{ $teacherNationalCode }})
                    </span>
                </x-slot:titleSuffix>
            @endif

            <x-slot:prefix>
                <x-image
                    :src="$teacherImage"
                    :fallbackSrc="asset('assets/image/avatar.png')"
                    :alt="$teacherName"
                    radius="full"
                    :width="40"
                    :height="40"
                />
            </x-slot:prefix>

            <x-slot:subtitle>
                <x-data-item
                    :value="$comment->created_at"
                    value-class="text-muted"
                    dir="ltr"
                />
            </x-slot:subtitle>

            <x-data-item
                :value="$comment->comment"
                value-class="text-justify"
            />
        </x-data-row>

        <div class="gy-4">
            <form
                id="comment_change_status"
                wire:submit="update"
            >
                {{-- TODO:: not load component and show error, for text-area too --}}
                <x-select
                    containerClass="col-12 col-md-8 col-lg-6"
                    :label="__('basemodule::field.status')"
                    name="status"
                    :items="$statuses"
                    wire:model="status"
                    id="status_selector"
                    :required="true"
                />
                @error('status')
                <div class="text-danger mt-1 mb-3">{{ $message }}</div>
                @enderror

                <x-text-area
                    containerClass="col-12"
                    :label="__('basemodule::field.admin_explanation')"
                    name="adminReply"
                    wire:model="adminReply"
                    :max-length="255"
                />
                @error('adminReply')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </form>
        </div>
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
