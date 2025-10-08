<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Bank\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class File
 *
 * @property int $id
 * @property string $name
 * @property string|null $uploader_type
 * @property int|null $uploader_id
 * @property int $file_type_id
 * @property int|null $file_disk_id
 * @property string|null $file_disk_name
 * @property string $mime_type
 * @property string $extension
 * @property int $size
 * @property string $path
 * @property string $full_month
 * @property string $original_name
 * @property bool $is_private
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 *
 * @package App\Models
 */
class File extends Model
{
	use SoftDeletes;
	protected $table = 'files';
	public static $snakeAttributes = false;

	protected $casts = [
		'uploader_id' => 'int',
		'file_type_id' => 'int',
		'file_disk_id' => 'int',
		'size' => 'int',
		'is_private' => 'bool',
		'is_deleted' => 'int',
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'name',
		'uploader_type',
		'uploader_id',
		'file_type_id',
		'file_disk_id',
		'file_disk_name',
		'mime_type',
		'extension',
		'size',
		'path',
		'full_month',
		'original_name',
		'is_private',
		'is_deleted',
		'deleted_by'
	];

}
