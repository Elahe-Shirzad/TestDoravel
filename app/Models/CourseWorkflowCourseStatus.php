<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
 * @package App\Models
 */
class CourseWorkflowCourseStatus extends Model
{
	protected $table = 'course_workflow_course_status';
	public $incrementing = false;
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'id' => 'int',
		'course_workflow_id' => 'int',
		'course_status_id' => 'int',
		'type' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'id',
		'course_workflow_id',
		'course_status_id',
		'type',
		'created_by'
	];
}
