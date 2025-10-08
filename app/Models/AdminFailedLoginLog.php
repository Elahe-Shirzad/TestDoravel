<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminFailedLoginLog
 * 
 * @property int $id
 * @property int $admin_id
 * @property string|null $ip
 * @property string|null $user_agent
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class AdminFailedLoginLog extends Model
{
	protected $table = 'admin_failed_login_logs';
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'admin_id' => 'int'
	];

	protected $fillable = [
		'admin_id',
		'ip',
		'user_agent'
	];
}
