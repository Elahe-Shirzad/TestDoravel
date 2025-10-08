<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Bank\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FileDisk
 *
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string|null $hostname
 * @property int|null $port
 * @property int $driver
 * @property string|null $base_path
 * @property int $auth_type
 * @property int $priority
 * @property array|null $auth_fields
 * @property array|null $options
 * @property string|null $description
 * @property Carbon $started_at
 * @property bool $is_expired
 * @property Carbon|null $expired_at
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
class FileDisk extends Model
{
	use SoftDeletes;
	protected $table = 'file_disks';
	public static $snakeAttributes = false;

	protected $casts = [
		'port' => 'int',
		'driver' => 'int',
		'auth_type' => 'int',
		'priority' => 'int',
		'auth_fields' => 'json',
		'options' => 'json',
		'started_at' => 'datetime',
		'is_expired' => 'bool',
		'expired_at' => 'datetime',
		'is_active' => 'bool',
		'is_deleted' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'title',
		'name',
		'hostname',
		'port',
		'driver',
		'base_path',
		'auth_type',
		'priority',
		'auth_fields',
		'options',
		'description',
		'started_at',
		'is_expired',
		'expired_at',
		'is_active',
		'is_deleted',
		'created_by',
		'updated_by',
		'deleted_by'
	];
}
