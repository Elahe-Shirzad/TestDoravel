<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Instructor\Models;

use App\Models\Admin;
use App\Models\File;
use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\Foundation\Core\Enums\IsDeleted;
use Dornica\Foundation\Core\Traits\SoftDeletes;
use Dornica\Foundation\Core\Traits\UserActivityTracking;
use Dornica\Foundation\Localization\Localized;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\BaseModule\Traits\AvailableScopeTrait;
use Modules\BaseModule\Traits\FormattedDate;
use Modules\Course\Models\Course;

/**
 * Class Instructor
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int|null $image_id
 * @property string|null $bio
 * @property string|null $phone
 * @property string $national_code
 * @property string $mobile
 * @property string|null $email
 * @property string|null $website
 * @property int $sort
 * @property bool $is_active
 * @property Carbon $created_at
 * @property int $created_by
 * @property Carbon $updated_at
 * @property int $updated_by
 * @property bool $is_deleted
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Admin $admin
 * @property File|null $file
 * @property Collection|Course[] $courses
 *
 * @package App\Models
 */
class Instructor extends Model
{
    use SoftDeletes, AvailableScopeTrait, UserActivityTracking, Localized, FormattedDate;

    protected $table = 'instructors';

    protected $casts = [
        'image_id' => 'int',
        'sort' => 'int',
        'is_active' => IsActive::class,
        'created_by' => 'int',
        'updated_by' => 'int',
        'is_deleted' => 'bool',
        'deleted_by' => IsDeleted::class
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'image_id',
        'bio',
        'phone',
        'national_code',
        'mobile',
        'email',
        'website',
        'sort',
        'is_active',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        // TODO:: check use Attribute
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if blog has any dependencies that would block deletion
     *
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return $this->getBlockingRelation() !== null;
    }

    /**
     * Get the name of the first blocking relation if exists
     *
     * @return string|null
     */
    public function getBlockingRelation(): ?string
    {
        $relations = [
            'courses' => 'courses',
        ];

        return collect($relations)->first(
            fn($value, $key) => $this->$key()->exists()
        );
    }

    /**
     * Get the translated error message for deletion blocking
     *
     * @return string|null
     */
    public function getDeleteErrorMessage(): ?string
    {
        if ($relation = $this->getBlockingRelation()) {
            return __("basemodule::message.delete_not_allow_cause_dependencies_with_param", [
                "sectionName" => __("basemodule::section.$relation"),
            ]);
        }
        return null;
    }

}
