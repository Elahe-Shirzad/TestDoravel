<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SmsProvider
 * 
 * @property int $id
 * @property string|null $name
 * @property string $provider_code
 * @property array $sms_numbers
 * @property string|null $verify_template_code
 * @property string|null $param1
 * @property string|null $param2
 * @property string|null $param3
 * @property string|null $param4
 * @property bool $is_default
 * @property bool $is_active
 * @property bool $is_expired
 * @property Carbon $started_at
 * @property Carbon|null $end_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $is_deleted
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class SmsProvider extends Model
{
	use SoftDeletes;
	protected $table = 'sms_providers';
	public static $snakeAttributes = false;

	protected $casts = [
		'sms_numbers' => 'json',
		'is_default' => 'bool',
		'is_active' => 'bool',
		'is_expired' => 'bool',
		'started_at' => 'datetime',
		'end_at' => 'datetime',
		'is_deleted' => 'int'
	];

	protected $fillable = [
		'name',
		'provider_code',
		'sms_numbers',
		'verify_template_code',
		'param1',
		'param2',
		'param3',
		'param4',
		'is_default',
		'is_active',
		'is_expired',
		'started_at',
		'end_at',
		'is_deleted'
	];
}
