<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Permission
 * 
 * @property int $id
 * @property int $permission_category_id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string $guard_name
 * @property string|null $style
 * @property string|null $extra_value
 * @property string|null $extra_param
 * @property string|null $description
 * @property bool $show_in_menu
 * @property bool $show_in_home
 * @property bool $is_active
 * @property bool $is_special
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $is_deleted
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Permission extends Model
{
	use SoftDeletes;
	protected $table = 'permissions';
	public static $snakeAttributes = false;

	protected $casts = [
		'permission_category_id' => 'int',
		'parent_id' => 'int',
		'show_in_menu' => 'bool',
		'show_in_home' => 'bool',
		'is_active' => 'bool',
		'is_special' => 'bool',
		'sort' => 'int',
		'is_deleted' => 'int'
	];

	protected $fillable = [
		'permission_category_id',
		'parent_id',
		'name',
		'slug',
		'guard_name',
		'style',
		'extra_value',
		'extra_param',
		'description',
		'show_in_menu',
		'show_in_home',
		'is_active',
		'is_special',
		'sort',
		'is_deleted'
	];
}
