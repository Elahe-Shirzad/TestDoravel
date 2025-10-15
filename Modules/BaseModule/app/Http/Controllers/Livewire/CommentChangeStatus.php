<?php

namespace Modules\BaseModule\Http\Controllers\Livewire;

use Livewire\Component;
use Modules\BaseModule\Enums\General\ConfirmationStatus;

class CommentChangeStatus extends Component
{
    public string|null $model = null;
    public string|int|null $commentId = null;

//    #[Validate('required')]
    public $status = '';

//    #[Validate('nullable|max:255')]
    public $adminReply = '';

    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => 'required',
            'adminReply' => 'nullable|max:255'
        ];
    }

    /**
     * validationAttributes
     *
     * @return array
     */
    public function validationAttributes(): array
    {
        return [
            'status' => __('basemodule::field.status'),
            'adminReply' => __('basemodule::general.fields.admin_reply')
        ];
    }

    /**
     * update
     *
     * @return void
     */
    public function update(): void
    {
        $this->validate();

        $this->model::where('id', $this->commentId)
            ->update([
                'admin_id' => authenticator()->id(),
                'status' => $this->status,
                'admin_reply' => $this->adminReply,
                'replied_at' => now()
            ]);

        $this->resetForm();
        $this->dispatch('show-change-status-toast');
    }

    /**
     * resetForm
     *
     * @return void
     */
    public function resetForm(): void
    {
        $this->reset(['status', 'adminReply']);
        $this->resetValidation();
    }

    /**
     * getCommentInfo
     *
     * @return mixed
     */
    public function getCommentInfo(): mixed
    {
        return $this->model::find($this->commentId);
    }

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
        $this->resetForm();
        $this->render();
    }

    public function render()
    {
        $comment = null;
        $statuses = [];
        if ($this->model && $this->commentId) {
            $comment = $this->getCommentInfo();
            $statuses = prepareSelectComponentData(
                source: ConfirmationStatus::class,
                moduleName: "basemodule"
            );
        }

        return view('basemodule::livewire.comment-change-status', data: compact(
            'comment',
            'statuses'
        ));
    }
}
