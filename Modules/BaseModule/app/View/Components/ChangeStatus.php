<?php

namespace Modules\BaseModule\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;

class ChangeStatus extends Component
{
    public string $modalTitle;
    public string $modalId;
    public string $formId;
    public string $routeName;
    public string $sectionNameInWorkflow;
    public string $statusModel;
    public string $selectBoxName;
    public string $formRequest;
    public int|string|null $modelId;
    public int|string|null $statusId;
    public string|array $parameterName;
    public string $statusAccessesRelation;
    public ?array $modalBadges;
    public bool $changeStatusInList;
    public ?array $relationsInResource;
    public ?array $fieldsForBadge;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string          $routeName,
        string          $sectionNameInWorkflow,
        string          $statusModel,
        string          $formRequest,
        int|string|null $modelId,
        int|string|null $statusId,
        string          $statusAccessesRelation,
        string          $modalTitle = 'تعیین وضعیت',
        string          $modalId = 'change-status',
        string          $formId = 'change-status-form',
        string          $selectBoxName = 'status_id',
        string|array          $parameterName = 'id',
        ?array          $modalBadges = [],
        bool  $changeStatusInList = false,
        array $relationsInResource = [],
        ?array $fieldsForBadge = []
    )
    {
        $this->modalTitle = $modalTitle;
        $this->modalId = $modalId;
        $this->formId = $formId;
        $this->routeName = $routeName;
        $this->sectionNameInWorkflow = $sectionNameInWorkflow;
        $this->statusModel = $statusModel;
        $this->selectBoxName = $selectBoxName;
        $this->formRequest = $formRequest;
        $this->modelId = decryptValueIfEncrypted($modelId);
        $this->statusId = decryptValueIfEncrypted($statusId);
        $this->parameterName = $parameterName;
        $this->statusAccessesRelation = $statusAccessesRelation;
        $this->fieldsForBadge = $fieldsForBadge;
        $this->modalBadges = $modalBadges;
        $this->changeStatusInList = $changeStatusInList;
        $this->relationsInResource = $relationsInResource;

        $this->validateRequiredIdsForNonListStatus();
    }

    /**
     * Validate that modelId and statusId are provided when changeStatusInList is false.
     *
     * @throws InvalidArgumentException
     */
    private function validateRequiredIdsForNonListStatus(): void
    {
        if (!$this->changeStatusInList && (empty($this->modelId) || empty($this->statusId))) {
            throw new InvalidArgumentException(
                'ChangeStatus component requires both modelId and statusId when changeStatusInList is false.'
            );
        }
    }

    public function render(): View|Closure|string
    {
        return view('basemodule::components.change-status');
    }
}
