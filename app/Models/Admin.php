<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Admin
 * 
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $mobile
 * @property string|null $email
 * @property string $password
 * @property string|null $job_title
 * @property string $national_code
 * @property string|null $image
 * @property int|null $login_type
 * @property int $status
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $mobile_verified_at
 * @property int $is_superadmin
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 *
 * @package App\Models
 */
class Admin extends Model
{
	use SoftDeletes;
	protected $table = 'admins';
	public static $snakeAttributes = false;

	protected $casts = [
		'login_type' => 'int',
		'status' => 'int',
		'email_verified_at' => 'datetime',
		'mobile_verified_at' => 'datetime',
		'is_superadmin' => 'int',
		'is_deleted' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'deleted_by' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'mobile',
		'email',
		'password',
		'job_title',
		'national_code',
		'image',
		'login_type',
		'status',
		'email_verified_at',
		'mobile_verified_at',
		'is_superadmin',
		'is_deleted',
		'created_by',
		'updated_by',
		'deleted_by'
	];
}
