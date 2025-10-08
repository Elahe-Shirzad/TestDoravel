<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PermissionCategory
 * 
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string|null $style
 * @property string|null $image
 * @property bool $show_in_menu
 * @property bool $show_in_home
 * @property bool $is_active
 * @property int $sort
 * @property int|null $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $is_deleted
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PermissionCategory extends Model
{
	use SoftDeletes;
	protected $table = 'permission_categories';
	public static $snakeAttributes = false;

	protected $casts = [
		'show_in_menu' => 'bool',
		'show_in_home' => 'bool',
		'is_active' => 'bool',
		'sort' => 'int',
		'parent_id' => 'int',
		'is_deleted' => 'int'
	];

	protected $fillable = [
		'slug',
		'name',
		'style',
		'image',
		'show_in_menu',
		'show_in_home',
		'is_active',
		'sort',
		'parent_id',
		'is_deleted'
	];
}
