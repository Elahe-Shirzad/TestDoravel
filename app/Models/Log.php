<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * 
 * @property int $id
 * @property string $admin_type
 * @property int $admin_id
 * @property int $admin_role_id
 * @property string $entity_type
 * @property int $entity_id
 * @property int $action
 * @property string $data
 * @property string $page_route_name
 * @property string $referer_route_name
 * @property string $ip
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class Log extends Model
{
	protected $table = 'logs';
	public $timestamps = false;
	public static $snakeAttributes = false;

	protected $casts = [
		'admin_id' => 'int',
		'admin_role_id' => 'int',
		'entity_id' => 'int',
		'action' => 'int'
	];

	protected $fillable = [
		'admin_type',
		'admin_id',
		'admin_role_id',
		'entity_type',
		'entity_id',
		'action',
		'data',
		'page_route_name',
		'referer_route_name',
		'ip'
	];
}
