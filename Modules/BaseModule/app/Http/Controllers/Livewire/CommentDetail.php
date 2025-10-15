<?php

namespace Modules\BaseModule\Http\Controllers\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\BaseModule\Enums\General\IsRead;

class CommentDetail extends Component
{
    public string|null $model = null;
    public string|int|null $commentId = null;

    /**
     * mount
     *
     * @param string $model
     * @param string|int|null $commentId
     * @return void
     */
    public function mount(string $model, string|int|null $commentId)
    {
        $this->model = $model;
        $this->commentId = $commentId;
    }

    /**
     * loadComment
     *
     * @param string $model
     * @param string|int|null $commentId
     * @return void
     */
    public function loadComment(string $model, string|int|null $commentId)
    {
        $this->model = $model;
        $this->commentId = $commentId;
        $this->render();
        $this->dispatch('refreshComments')->to('comments');
    }

    /**
     * updateCommentIsReadStatus
     *
     * @return void
     */
    public function updateCommentIsReadStatus(): void {
        $this->model::query()
            ->where('id', $this->commentId)
            ->update([
                'is_read' => IsRead::YES,
                'read_at' => now()
            ]);
    }

    public function render()
    {
        $comment = null;
        if ($this->model && $this->commentId) {
            $comment = $this->model::query()
                ->where('id', $this->commentId)
                ->with(['teacher' => function ($query) {
                    $query->select(
                        'id',
                        'national_code',
                        'first_name',
                        'last_name',
                        DB::raw("CONCAT(first_name, ' ', last_name) as full_name")
                    );
                }, 'admin' => function ($query) {
                    $query->select(
                        'id',
                        'national_code',
                        'first_name',
                        'last_name',
                        DB::raw("CONCAT(first_name, ' ', last_name) as full_name")
                    );
                }])
                ->first();

            if ($comment && $comment->is_read == IsRead::NO) {
                $this->updateCommentIsReadStatus();
            }
        }

        return view('basemodule::livewire.comment-detail', data: compact('comment'));
    }
}
