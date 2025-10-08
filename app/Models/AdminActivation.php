<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminActivation
 * 
 * @property int $id
 * @property int $admin_id
 * @property string $code
 * @property string|null $hashcode
 * @property array|null $extra_data
 * @property int $target_type
 * @property string $target
 * @property int $type
 * @property bool $is_used
 * @property Carbon|null $used_at
 * @property Carbon|null $expired_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class AdminActivation extends Model
{
	protected $table = 'admin_activations';
	public static $snakeAttributes = false;

	protected $casts = [
		'admin_id' => 'int',
		'extra_data' => 'json',
		'target_type' => 'int',
		'type' => 'int',
		'is_used' => 'bool',
		'used_at' => 'datetime',
		'expired_at' => 'datetime'
	];

	protected $fillable = [
		'admin_id',
		'code',
		'hashcode',
		'extra_data',
		'target_type',
		'target',
		'type',
		'is_used',
		'used_at',
		'expired_at'
	];
}
