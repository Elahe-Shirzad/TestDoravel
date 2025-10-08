<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $guard_name
 * @property int $login_type
 * @property int $sort
 * @property bool $is_active
 * @property bool $is_superrole
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 *
 * @package App\Models
 */
class Role extends Model
{
	use SoftDeletes;
	protected $table = 'roles';
	public static $snakeAttributes = false;

	protected $casts = [
		'login_type' => 'int',
		'sort' => 'int',
		'is_active' => 'bool',
		'is_superrole' => 'bool',
		'is_deleted' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'name',
		'slug',
		'guard_name',
		'login_type',
		'sort',
		'is_active',
		'is_superrole',
		'is_deleted',
		'created_by',
		'updated_by',
		'deleted_by'
	];
}
