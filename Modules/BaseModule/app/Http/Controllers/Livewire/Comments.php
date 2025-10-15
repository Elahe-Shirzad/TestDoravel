<?php

namespace Modules\BaseModule\Http\Controllers\Livewire;

use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public string $section;
    public string|int $entityId;
    public string $entityModel;
    public string $commentModel;
    public string $entityCommentRelation;
    public array $queryConditions;
    public Collection $statistics;
    public array $chartData;
    public string $entityBannerSource;
    public string $entitySectionSource;
    public string $search = '';
    public array $selectedComments = [];
    public string|int|null $selectedComment = null;
    public string|int|null $relatedCommentId = null;
    public string $deleteRouteName;
    public int $perPage;
    #[Url('listPage')]
    public int $listPage = 1;

    protected $listeners = [
        'showComment' => 'showComment',
        'showRelatedComments' => 'showRelatedComments',
        'showCommentChangeStatus' => 'showCommentChangeStatus',
        'refreshComments' => '$refresh'
    ];

    /**
     * mount
     *
     * @return void
     */
    public function mount(
        string     $section,
        string|int $entityId,
        string     $entityModel,
        string     $commentModel,
        string     $entityCommentRelation,
        Collection $statistics,
        array $chartData,
        string     $entityBannerSource,
        string     $entitySectionSource,
        string     $deleteRouteName,
        array      $queryConditions = [],
        int        $perPage = 0
    ): void
    {
        $this->section = $section;
        $this->entityId = $entityId;
        $this->entityModel = $entityModel;
        $this->commentModel = $commentModel;
        $this->entityCommentRelation = $entityCommentRelation;
        $this->queryConditions = $queryConditions;
        $this->statistics = $statistics;
        $this->chartData = $chartData;
        $this->entityBannerSource = $entityBannerSource;
        $this->entitySectionSource = $entitySectionSource;
        $this->deleteRouteName = $deleteRouteName;
        $this->perPage = $perPage > 0 ? $perPage : config('dornica-panel-kit.table_generator.per_page', 10);
    }

    /**
     * getEntityInfo
     *
     * @return mixed
     */
    public function getEntityInfo(): mixed
    {
        return $this->entityModel::findOrFail($this->entityId);
    }

    /**
     * updatedSearch
     *
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage('listPage');
    }

    /**
     * bulkDelete
     *
     * @return void
     */
    public function bulkDelete(): void
    {
        if (empty($this->selectedComments)) {
            $this->dispatch('show-empty-bulk-delete-items-toast');
            return;
        }

        // Perform bulk delete
        $this->getCommentModelQuery()
            ->whereIn('id', $this->selectedComments)
            ->delete();

        $this->selectedComments = [];
        $this->dispatch('show-bulk-delete-successfully-toast');

        $this->resetPage('listPage');
    }

    /**
     * showComment
     *
     * @param string|int|null $commentId
     * @return void
     */
    public function showComment(string|int|null $commentId): void
    {
        $this->selectedComment = $commentId;
        $this->dispatch('show-comment-detail-modal', [
            'id' => 'showCommentModal',
            'model' => $this->commentModel,
            'commentId' => $commentId
        ]);
    }

    /**
     * showRelatedComments
     *
     * @param string|int|null $commentId
     * @return void
     */
    public function showRelatedComments(string|int|null $commentId): void
    {
        $this->relatedCommentId = $commentId;
        $this->dispatch('show-related-comment-modal', [
            'id' => 'showRelatedCommentsModal',
            'model' => $this->commentModel,
            'relatedCommentId' => $commentId
        ]);
    }

    /**
     * showCommentChangeStatus
     *
     * @param string|int|null $commentId
     * @return void
     */
    public function showCommentChangeStatus(string|int|null $commentId): void
    {
        $this->selectedComment = $commentId;
        $this->dispatch('show-comment-change-status-modal', [
            'id' => 'showCommentChangeStatusModal',
            'model' => $this->commentModel,
            'commentId' => $commentId
        ]);
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
     * getCommentModelQuery
     *
     * @return mixed
     */
    public function getCommentModelQuery(): mixed
    {
        $query = $this->commentModel::query();
        $conditions = $this->queryConditions;

        foreach ($conditions as $condition) {
            $method = $condition['method'];
            $args = $condition['args'];
            $query = call_user_func_array([$query, $method], $args);
        }

        return $query;
    }

    public function render()
    {
        $entityInfo = $this->getEntityInfo();

        BladeLayout::data([$this->section => $entityInfo]);
        BladeLayout::banner($this->entityBannerSource);
        BladeLayout::section($this->entitySectionSource);

        $comments = $this->getCommentModelQuery()
            ->when($this->search, function ($query) {
                $query
                    ->where(function ($subQuery) {
                        $subQuery
                            ->orWhere('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhereHas('teacher', function ($q) {
                                $q
                                    ->whereRaw("CONCAT(first_name, ' ', last_name) like ?", ['%' . $this->search . '%'])
                                    ->orWhere('email', 'like', '%' . $this->search . '%')
                                    ->orWhere('national_code', 'like', '%' . $this->search . '%');
                            })
                            ->orWhere('comment', 'like', '%' . $this->search . '%');
                    });
            })
            ->paginate($this->perPage, ['*'], 'listPage');

        return view('basemodule::livewire.comments', data: compact(
            'entityInfo',
            'comments'
        ));
    }
}
