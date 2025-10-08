<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Bank\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FileDirectory
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 *
 * @package App\Models
 */
class FileDirectory extends Model
{
	use SoftDeletes;
	protected $table = 'file_directories';
	public static $snakeAttributes = false;

	protected $casts = [
		'is_active' => 'bool',
		'is_deleted' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'name',
		'slug',
		'is_active',
		'is_deleted',
		'created_by',
		'updated_by',
		'deleted_by'
	];
}
