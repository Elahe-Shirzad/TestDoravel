<?php

namespace Modules\Bank\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\Bank\Enums\WorkFlowType;

class WorkFlowService
{
    protected string $modelClass;
    protected string $workflowClass;
    protected string $workflowRelation;
    protected string $workflowIdColumnName;
    protected string $statusIdColumnName;
    protected string $startColumnName;
    protected string $endColumnName;
    protected string $defaultGuard;
    public ?string $workflowId;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->modelClass = $config['modelClass'];
        $this->workflowClass = $config['workflowClass'];
        $this->workflowRelation = $config['workflowRelation'];
        $this->workflowIdColumnName = $config['workflowIdColumnName'];
        $this->statusIdColumnName = $config['statusIdColumnName'];
        $this->startColumnName = $config['startColumnName'] ?? 'start_date';
        $this->endColumnName = $config['endColumnName'] ?? 'end_date';
        $this->defaultGuard = $config['defaultGuard'] ?? 'admin';
        $this->workflowId = $config['workflowId'] ?? null;
    }

    /**
     * @return Builder
     */
    public function getModelQuery(): Builder
    {
        return $this->modelClass::query();
    }

    /**
     * Get workflow IDs.
     *
     * @return array
     */
    public function getWorkFlowIDs(): array
    {
        return $this->workflowId
            ? [$this->workflowId]
            : $this->getExistingWorkFlow();
    }

    /**
     * @return mixed
     */
    public function getWorkFlow(): mixed
    {
        return $this->workflowClass::find('id', $this->workflowId);
    }

    /**
     * @return array
     */
    public function getExistingWorkFlow(): array
    {
        $guard = config('dornica-app.default_guard', $this->defaultGuard);
        $user = auth($guard)->user();

        if (!$user) {
            return [];
        }

        $userRoleId = authenticator()->currentRole()['id'] ?? null;

        if (!$userRoleId) {
            return [];
        }

        return $user->roles()
            ->whereKey($userRoleId)
            ->with([$this->workflowRelation => function ($query) {
                $query->availableQuery()
                    ->where($this->startColumnName, '<=', now()->format(jdateFormat('date_dash')))
                    ->where($this->endColumnName, '>=', now()->format(jdateFormat('date_dash')));
            }])
            ->get()
            ->flatMap(fn($role) => $role->{$this->workflowRelation}->pluck('id'))
            ->toArray();
    }

    /**
     * @param int $statusType
     * @return array
     */
    public function getWorkFlowStatusIds(int $statusType): array
    {
        $workflowIds = $this->getWorkFlowIDs();

        // Return empty array if no workflow IDs found
        if (empty($workflowIds)) {
            return [];
        }

        return $this->getModelQuery()
            ->whereIn($this->workflowIdColumnName, $workflowIds)
            ->where('type', $statusType)
            ->get()
            ->pluck($this->statusIdColumnName)
            ->unique()
            ->toArray();
    }

    /**
     * @return array
     */
    public function getAllWorkFlowStatuses(): array
    {
        $viewStatuses = $this->getWorkFlowStatusIds(WorkFlowType::VIEW->value);
        $changeStatuses = $this->getWorkFlowStatusIds(WorkFlowType::CHANGE->value);
        $setStatuses = $this->getWorkFlowStatusIds(WorkFlowType::SET->value);

        return [
            'view' => $viewStatuses,
            'change' => $changeStatuses,
            'set' => $setStatuses,
        ];
    }

    /**
     * @return array
     */
    public function getViewWorkFlowStatusIds(): array
    {
        return $this->getWorkFlowStatusIds(WorkFlowType::VIEW->value);
    }

    /**
     * @return array
     */
    public function getChangeWorkFlowStatusIds(): array
    {
        return $this->getWorkFlowStatusIds(WorkFlowType::CHANGE->value);
    }

    /**
     * @return array
     */
    public function getSetWorkFlowStatusIds(): array
    {
        return $this->getWorkFlowStatusIds(WorkFlowType::SET->value);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function viewable(Builder $query): Builder
    {
        $statusIds = $this->getWorkFlowStatusIds(WorkFlowType::VIEW->value);

        // If no viewable status IDs, return query that will yield no results
        if (empty($statusIds)) {
            return $query->where('id', 0); // Force no results
        }

        return $query->whereIn($this->statusIdColumnName, $statusIds);
    }

    /**
     * @param int $statusId
     * @return bool
     */
    public function changeable(int $statusId): bool
    {
        $statusIds = $this->getWorkFlowStatusIds(WorkFlowType::CHANGE->value);

        // Return false if no changeable status IDs available
        if (empty($statusIds)) {
            return false;
        }

        return in_array($statusId, $statusIds);
    }

    /**
     * @param int $statusId
     * @return bool
     */
    public function settable(int $statusId): bool
    {
        $statusIds = $this->getWorkFlowStatusIds(WorkFlowType::SET->value);

        // Return false if no settable status IDs available
        if (empty($statusIds)) {
            return false;
        }

        return in_array($statusId, $statusIds);
    }
}
