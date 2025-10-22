<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\CourseWorkflow\Models;

use App\Models\Admin;
use App\Models\Role;
use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\Foundation\Core\Enums\IsDeleted;
use Dornica\Foundation\Core\Traits\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\BaseModule\Traits\AvailableScopeTrait;
use Modules\BaseModule\Traits\FormattedDate;
use Modules\CourseStatus\Models\CourseStatus;

/**
 * Class CourseWorkflow
 *
 * @property int $id
 * @property int $role_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon $created_at
 * @property int $created_by
 * @property Carbon $updated_at
 * @property int $updated_by
 * @property bool $is_deleted
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Admin $admin
 * @property Role $role
 * @property Collection|CourseStatus[] $course_statuses
 *
 * @package App\Models
 */
class CourseWorkflow extends Model
{
    use SoftDeletes,AvailableScopeTrait,FormattedDate;
    protected $table = 'course_workflows';

    protected $casts = [
        'role_id' => 'int',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => IsActive::class,
        'created_by' => 'int',
        'updated_by' => 'int',
        'is_deleted' => IsDeleted::class,
        'deleted_by' => 'int'
    ];

    protected $fillable = [
        'role_id',
        'start_date',
        'end_date',
        'description',
        'is_active',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function course_statuses()
    {
        return $this->belongsToMany(CourseStatus::class, 'course_workflow_course_status')
            ->withPivot('id', 'type', 'created_by');
    }

    /**
     * @return HasMany
     */
    public function courseWorkflowCourseStatuses(): HasMany
    {
        return $this->hasMany(CourseWorkflowCourseStatus::class, 'course_workflow_id');
    }
}
