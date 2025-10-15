<?php

namespace Modules\BaseModule\Http\Controllers\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedComment extends Component
{
    use WithPagination;

    public string|null $model = null;
    public string|int|null $relatedCommentId = null;
    public int $perPage;
    #[Url('modalPage')]
    public int $modalPage = 1;

    /**
     * mount
     *
     * @param  mixed $model
     * @param  mixed $relatedCommentId
     * @param  mixed $perPage
     * @return void
     */
    public function mount(
        string $model,
        string|int|null $relatedCommentId,
        int $perPage = 0
    ): void
    {
        $this->model = $model;
        $this->relatedCommentId = $relatedCommentId;
        $this->perPage = $perPage > 0 ? $perPage : config('dornica-panel-kit.table_generator.per_page', 10);
    }

    /**
     * paginationView
     *
     * @return string
     */
    public function paginationView(): string
    {
        return 'blade-components::UI.Table.views.pagination';
    }

    /**
     * loadComment
     *
     * @param string $model
     * @param string|int|null $relatedCommentId
     * @return void
     */
    public function loadComment(string $model, string|int|null $relatedCommentId): void
    {
        $this->model = $model;
        $this->relatedCommentId = $relatedCommentId;
        $this->resetPage('modalPage');
        $this->render();
    }

    /**
     * getRelatedCommentQuery
     *
     * @return mixed
     */
    public function getRelatedCommentQuery():mixed {
        return $this->model::query()
            ->where('parent_id', $this->relatedCommentId);
    }

    public function render()
    {
        $relatedComments = null;
        $totalRelatedComments = 0;
        if ($this->model && $this->relatedCommentId) {
            $totalRelatedComments = $this->getRelatedCommentQuery()
                ->count();

            $relatedComments = $this->getRelatedCommentQuery()
                ->with(['teacher' => function ($query) {
                    $query->select(
                        'id',
                        'national_code',
                        'first_name',
                        'last_name',
                        'avatar_id',
                        DB::raw("CONCAT(first_name, ' ', last_name) as full_name")
                    );
                }])
                ->paginate($this->perPage, ['*'], 'modalPage');
        }

        return view('basemodule::livewire.related-comment', data: compact(
            'relatedComments',
            'totalRelatedComments'
        ));
    }
}
