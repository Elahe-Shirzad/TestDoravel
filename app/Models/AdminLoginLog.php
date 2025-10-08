<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminLoginLog
 * 
 * @property int $id
 * @property int $admin_id
 * @property int|null $admin_role_id
 * @property string $ip
 * @property string $user_agent
 * @property int $login_type
 * @property Carbon $logged_in_at
 * @property Carbon|null $logged_out_at
 *
 * @package App\Models
 */
class AdminLoginLog extends Model
{
	protected $table = 'admin_login_logs';
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'admin_id' => 'int',
		'admin_role_id' => 'int',
		'login_type' => 'int',
		'logged_in_at' => 'datetime',
		'logged_out_at' => 'datetime'
	];

	protected $fillable = [
		'admin_id',
		'admin_role_id',
		'ip',
		'user_agent',
		'login_type',
		'logged_in_at',
		'logged_out_at'
	];
}
