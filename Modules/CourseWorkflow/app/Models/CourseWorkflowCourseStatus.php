<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\CourseWorkflow\Models;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\BaseModule\Enums\General\WorkFlowType;
use Modules\CourseStatus\Models\CourseStatus;

/**
 * Class CourseWorkflowCourseStatus
 *
 * @property int $id
 * @property int $course_workflow_id
 * @property int $course_status_id
 * @property int $type
 * @property Carbon $created_at
 * @property int $created_by
 *
 * @property Admin $admin
 * @property CourseStatus $course_status
 * @property CourseWorkflow $course_workflow
 *
 * @package App\Models
 */
class CourseWorkflowCourseStatus extends Model
{
    protected $table = 'course_workflow_course_status';
    public $timestamps = false;

    protected $casts = [
        'course_workflow_id' => 'int',
        'course_status_id' => 'int',
        'type' => 'int',
        'created_by' => 'int'
    ];

    protected $fillable = [
        'course_workflow_id',
        'course_status_id',
        'type',
        'created_by'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function course_status()
    {
        return $this->belongsTo(CourseStatus::class);
    }

    public function course_workflow()
    {
        return $this->belongsTo(CourseWorkflow::class);
    }

    /**
     * @param int $workflowId
     * @param array $statusIds
     * @param WorkFlowType $type
     * @return mixed
     */
    public static function bulkAssignStatuses(int $workflowId, array $statusIds, WorkflowType $type): mixed
    {
        if (empty($statusIds)) {
            return false;
        }

        $records = array_map(function ($statusId) use ($workflowId, $type) {
            return [
                'course_workflow_id' => $workflowId,
                'course_status_id' => $statusId,
                'type' => $type->value,
                'created_at' => now(),
                'created_by' => auth()->guard('admin')->user()->id ?? null,
            ];
        }, $statusIds);
        return self::insert($records);
    }
}
