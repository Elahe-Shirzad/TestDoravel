<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RolePermission
 * 
 * @property int $id
 * @property int $permission_id
 * @property int $role_id
 * @property Carbon $created_at
 * @property int|null $created_by
 *
 * @package App\Models
 */
class RolePermission extends Model
{
	protected $table = 'role_permission';
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'permission_id' => 'int',
		'role_id' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'permission_id',
		'role_id',
		'created_by'
	];
}
