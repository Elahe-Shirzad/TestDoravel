<div>
    @if($relatedComments && $relatedComments->count())
        @forelse($relatedComments as $comment)
            @php
                $teacherName = $comment->teacher ? $comment->teacher->full_name : $comment->name;
                $teacherImage = $comment->teacher ? $comment->teacher->avatar_id : null;
            @endphp

            <x-data-row
                :title="$teacherName"
                class="text-bg-light mb-3"
            >
                <x-slot name="prefix">
                    <x-image
                        :src="$teacherImage"
                        :fallbackSrc="asset('assets/image/avatar.png')"
                        :alt="$teacherName"
                        radius="full"
                        :width="40"
                        :height="40"
                    />
                </x-slot>

                <x-slot name="subtitle">
                    <x-data-item
                        :value="$comment->created_at"
                        value-class="text-muted"
                        dir="ltr"
                    />
                </x-slot>

                <x-data-item
                    :value="$comment->comment"
                    value-class="text-justify"
                />
            </x-data-row>
        @empty
            <x-empty-state
                :title="__('basemodule::message.no_info_exists')"
            />
        @endforelse

        @if ($relatedComments->hasPages())
            <div class="x-table-pagination d-flex justify-content-center gap-3 align-items-center mt-2">
                {{ $relatedComments->links() }}
            </div>
        @endif
    @else
        <x-empty-state
            container-class="empty_component"
            :title="__('basemodule::message.loading_for_get_data')"
        />
    @endif
</div>

@pushonce('scripts')
    <script>
        document.addEventListener('shown.bs.modal', function () {
            // updateTotalBadge();
        });

        $(function () {
            toggleLoading('.empty_component');
        });
    </script>
@endpushonce
