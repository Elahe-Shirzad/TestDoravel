<?php

namespace Modules\BaseModule\Jobs;

use App\Models\Admin;
use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsActive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Enums\General\ContentStatus;

class ChangeStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param string $model
     * @param string $statusName
     * @param string $statusModel
     * @param ContentStatus $status
     * @param string $entity
     * @param string $logModel
     */
    public function __construct(
        protected string $model,
        protected string $statusName,
        protected string $statusModel,
        protected ContentStatus $status = ContentStatus::PUBLISHED,
        protected string $entity,
        protected string $logModel
    )
    {
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        match ($this->status) {
            ContentStatus::EXPIRED => $this->changeStatus('expired', [
                'current' => ['is_publish' => BooleanState::YES],
                'next' => ['is_expired' => BooleanState::YES],
                'dateCol' => 'expired_at',
                'dateOp' => '<=',
                'setDate' => true,
            ]),
            ContentStatus::PUBLISHED => $this->changeStatus('published', [
                'current' => ['is_draft' => BooleanState::YES],
                'next' => ['is_publish' => BooleanState::YES],
                'dateCol' => 'published_at',
                'dateOp' => '<=',
                'setDate' => true,
            ]),
        };
    }

    /**
     * Get a status record by conditions
     * @param array $flags
     * @return mixed
     */
    protected function getStatus(array $flags): mixed
    {
        return $this->statusModel::query()
            ->where($flags)
            ->where('is_active', IsActive::YES)
            ->first();
    }

    /**
     * Log missing statuses
     * @param string $type
     * @param array $missing
     * @return void
     */
    protected function logStatusError(string $type, array $missing): void
    {
        Log::channel('change-status-job')->error(sprintf(
            '[Status Change] ❌ Failed for "%s" at %s — Missing: %s',
            $type,
            now()->toDateTimeString(),
            implode(' & ', $missing)
        ));
    }

    /**
     * Generic status change handle
     * @param string $type
     * @param array $config
     * @return void
     */
    protected function changeStatus(string $type, array $config): void
    {
        $currentStatus = $this->getStatus($config['current']);
        $nextStatus = $this->getStatus($config['next']);

        if (!$currentStatus || !$nextStatus) {
            $this->logStatusError($type, array_filter([
                !$currentStatus ? key($config['current']) : null,
                !$nextStatus ? key($config['next']) : null,
            ]));
            return;
        }

        $now = Carbon::now();
        $formattedNow = $now->format(jdateFormat('datetime_minute_with_zero'));

        $query = $this->model::query()->where($this->statusName, $currentStatus->id);

        // Published logic: published_at can be null or <= now
        if ($type === 'published') {
            $query->where(function ($q) use ($now) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', $now);
            });
        }

        // Expired logic: expired_at must exist and <= now
        if ($type === 'expired') {
            $query->whereNotNull('expired_at')
                ->where('expired_at', '<=', $now);
        }

        $entityIds = $query->pluck('id')->all();
        if (empty($entityIds)) {
            return;
        }

        // Update entities
        $updateData = [$this->statusName => $nextStatus->id];
        if ($config['setDate']) {
            $updateData[$config['dateCol']] = $formattedNow;
        }

        $query->update($updateData);

        // Insert logs in bulk
        $admin = Admin::where('national_code', '1234567891')->first();
        if ($admin) {
            $logs = array_map(fn($id) => [
                'admin_id' => $admin->id,
                $this->entity . '_status_id' => $nextStatus->id,
                $this->entity . '_id' => $id,
                'created_at' => $now,
                'created_by' => $admin->id
            ], $entityIds);

            $this->logModel::insert($logs);
        } else {
            Log::channel('change-status-job')->error('Admin system not FOUND!!!');
        }
    }
}
