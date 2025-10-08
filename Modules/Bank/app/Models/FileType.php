<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Bank\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FileType
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $type
 * @property int $file_directory_id
 * @property int $max_size
 * @property string|null $allowed_extensions
 * @property bool $is_required
 * @property bool $is_private
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
class FileType extends Model
{
	use SoftDeletes;
	protected $table = 'file_types';
	public static $snakeAttributes = false;

	protected $casts = [
		'type' => 'int',
		'file_directory_id' => 'int',
		'max_size' => 'int',
		'is_required' => 'bool',
		'is_private' => 'bool',
		'is_active' => 'bool',
		'is_deleted' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'name',
		'code',
		'type',
		'file_directory_id',
		'max_size',
		'allowed_extensions',
		'is_required',
		'is_private',
		'is_active',
		'is_deleted',
		'created_by',
		'updated_by',
		'deleted_by'
	];
}
