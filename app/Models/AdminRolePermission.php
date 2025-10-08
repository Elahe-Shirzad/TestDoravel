<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRolePermission
 * 
 * @property int $id
 * @property int $admin_id
 * @property int $admin_role_id
 * @property int $permission_id
 * @property Carbon $created_at
 * @property Carbon|null $expired_at
 * @property int|null $created_by
 * @property int|null $expired_by
 *
 * @package App\Models
 */
class AdminRolePermission extends Model
{
	protected $table = 'admin_role_permissions';
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'admin_id' => 'int',
		'admin_role_id' => 'int',
		'permission_id' => 'int',
		'expired_at' => 'datetime',
		'created_by' => 'int',
		'expired_by' => 'int'
	];

	protected $fillable = [
		'admin_id',
		'admin_role_id',
		'permission_id',
		'expired_at',
		'created_by',
		'expired_by'
	];
}
