<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AdminRole
 *
 * @property int $id
 * @property int $admin_id
 * @property int $role_id
 * @property bool $is_active
 * @property bool $is_default
 * @property Carbon|null $started_at
 * @property Carbon|null $expired_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 *
 *
 * @property Admin $admin
 * @property Role $role
 * @package App\Models
 */
class AdminRole extends Model
{
	use SoftDeletes;
	protected $table = 'admin_roles';
	public static $snakeAttributes = false;

	protected $casts = [
		'admin_id' => 'int',
		'role_id' => 'int',
		'is_active' => 'bool',
		'is_default' => 'bool',
		'started_at' => 'datetime',
		'expired_at' => 'datetime',
		'is_deleted' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int',
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'admin_id',
		'role_id',
		'is_active',
		'is_default',
		'started_at',
		'expired_at',
		'is_deleted',
		'created_by',
		'updated_by',
		'deleted_by'
	];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
