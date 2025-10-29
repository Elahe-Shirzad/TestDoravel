<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Dornica\AccessHub\Authentication\Models\User;
use Illuminate\Database\Eloquent\Collection;

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
 * @property Collection|Role[] $roles
 *
 * @package App\Models
 */
class Admin extends User
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_roles')
            ->withPivot('deleted_at');
    }
}
