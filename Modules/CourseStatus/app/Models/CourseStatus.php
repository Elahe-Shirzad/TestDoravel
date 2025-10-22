<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\CourseStatus\Models;

use App\Models\Admin;
use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\Foundation\Core\Traits\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Traits\AvailableScopeTrait;
use Modules\BaseModule\Traits\FormattedDate;
use Modules\CourseWorkflow\Models\CourseWorkflow;


/**
 * Class CourseStatus
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $color
 * @property int $is_start
 * @property int $is_end
 * @property int $is_count
 * @property int $is_lock
 * @property int $is_expired
 * @property int $can_update
 * @property int $can_delete
 * @property int $is_publish
 * @property string|null $description
 * @property int $sort
 * @property int $is_active
 * @property Carbon $created_at
 * @property int $created_by
 * @property Carbon $updated_at
 * @property int $updated_by
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Admin $admin
 *
 * @package App\Models
 */
class CourseStatus extends Model
{
	use SoftDeletes ,FormattedDate, AvailableScopeTrait;
	protected $table = 'course_statuses';
	public static $snakeAttributes = false;

	protected $casts = [
        'is_start' => BooleanState::class,
		'is_end' => BooleanState::class,
		'is_count' => BooleanState::class,
		'is_lock' => BooleanState::class,
		'is_expired' => BooleanState::class,
		'can_update' => BooleanState::class,
		'can_delete' => BooleanState::class,
		'is_publish' => BooleanState::class,
		'sort' => 'int',
		'is_active' => IsActive::class,
		'created_by' => 'int',
		'updated_by' => 'int',
		'is_deleted' => BooleanState::class,
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'color',
		'is_start',
		'is_end',
		'is_count',
		'is_lock',
		'is_expired',
		'can_update',
		'can_delete',
		'is_publish',
		'description',
		'sort',
		'is_active',
		'created_by',
		'updated_by',
		'is_deleted',
		'deleted_by'
	];
    /**
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * @return CourseStatus|HasMany
     */
//    public function courseLogs(): CourseStatus|HasMany
//    {
//        return $this->hasMany(CourseLog::class);
//    }

    /**
     * @return BelongsToMany
     */
    public function courseStatusAccesses(): BelongsToMany
    {
        return $this->belongsToMany(CourseStatus::class, 'course_status_access', 'course_status_id', 'child_course_status_id')
            ->withPivot('created_by')
            ->using(CourseStatusAccess::class);
    }

    /**
     * @return HasMany
     */
    public function courseStatuses(): HasMany
    {
        return $this->hasMany(CourseStatusAccess::class, 'child_course_status_id');
    }

    /**
     * @return BelongsToMany
     */
    public function courseWorkflows(): BelongsToMany
    {
        return $this->belongsToMany(CourseWorkflow::class, 'course_workflow_course_status')
            ->withPivot('id', 'type', 'created_by');
    }

    /**
     * @return CourseStatus|HasMany
     */
//    public function courses(): CourseStatus|HasMany
//    {
//        return $this->hasMany(\Modules\Course\Models\Course::class);
//    }

    /**
     * @return self|null
     */
    public static function activeStartStatus(): ?self
    {
        return self::where('is_start', BooleanState::YES)
            ->where('is_active', IsActive::YES)
            ->first();
    }

    public static function checkIfStartStatusExists(): bool
    {
        return self::where('is_active', IsActive::YES->value)
            ->where('is_start', BooleanState::YES->value)
            ->exists();
    }

    public static function changeTheActiveStatus($targetField, $exceptId = null): void
    {
        $query = self::where($targetField, BooleanState::YES->value);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        $query->update([$targetField => BooleanState::NO->value]);
    }

    public function isActiveStart(): bool
    {
        return $this->is_start->value === BooleanState::YES->value &&
            $this->is_active->value === IsActive::YES->value;
    }

    public function isLocked(): bool
    {
        return $this->is_lock->value === BooleanState::YES->value;
    }
}
