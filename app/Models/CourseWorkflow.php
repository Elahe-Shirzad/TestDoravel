<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CourseWorkflow
 * 
 * @property int $id
 * @property int $role_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string|null $description
 * @property int $is_active
 * @property Carbon $created_at
 * @property int $created_by
 * @property Carbon $updated_at
 * @property int $updated_by
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 *
 * @package App\Models
 */
class CourseWorkflow extends Model
{
	use SoftDeletes;
	protected $table = 'course_workflows';
	public $incrementing = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'id' => 'int',
		'role_id' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'is_active' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'is_deleted' => 'int',
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'id',
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
}
