<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * 
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $value
 * @property string $field
 * @property string $description
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Setting extends Model
{
	protected $table = 'settings';
	public static $snakeAttributes = false;

	protected $casts = [
		'is_active' => 'bool'
	];

	protected $fillable = [
		'key',
		'name',
		'value',
		'field',
		'description',
		'is_active'
	];
}
