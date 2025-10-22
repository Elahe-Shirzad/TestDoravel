<?php

namespace Modules\CourseCategory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CourseCategory\Database\Factories\CourseCategoryFactory;

use App\Models\Admin;
use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\Foundation\Core\Enums\IsDeleted;
use Dornica\Foundation\Core\Traits\SoftDeletes;
use Dornica\Foundation\Core\Traits\UserActivityTracking;
use Dornica\Foundation\FileManager\Models\File;
use Illuminate\Database\Eloquent\Collection;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Enums\General\SeoRobot;
use Modules\BaseModule\Enums\General\UserType;
use Modules\BaseModule\Traits\AvailableScopeTrait;
use Modules\BaseModule\Traits\FormattedDate;
use Modules\Course\Models\Course;

/**
 * Class CourseCategory
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $image_id
 * @property int|null $header_id
 * @property string $title
 * @property string $slug
 * @property string|null $style
 * @property string|null $description
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property bool|null $seo_robots
 * @property string|null $seo_keywords
 * @property bool $show_in_home
 * @property bool $show_in_menu
 * @property int $user_type
 * @property bool $is_special
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
 * @property CourseCategory|null $course_category
 * @property Collection|CourseCategory[] $course_categories
 * @property Collection|Course[] $courses
 *
 * @package App\Models
 */
class CourseCategory extends Model
{
    use SoftDeletes,
        AvailableScopeTrait,
        UserActivityTracking,
        FormattedDate;

    protected $table = 'course_categories';

    protected $casts = [
        'parent_id' => 'int',
        'image_id' => 'int',
        'header_id' => 'int',
        'seo_robots' => SeoRobot::class,
        'show_in_home' => BooleanState::class,
        'show_in_menu' => BooleanState::class,
        'user_type' => UserType::class,
        'is_special' => BooleanState::class,
        'sort' => 'int',
        'is_active' => IsActive::class,
        'created_by' => 'int',
        'updated_by' => 'int',
        'is_deleted' => IsDeleted::class,
        'deleted_by' => 'int'
    ];

    protected $fillable = [
        'parent_id',
        'image_id',
        'header_id',
        'title',
        'slug',
        'style',
        'description',
        'seo_title',
        'seo_description',
        'seo_robots',
        'seo_keywords',
        'show_in_home',
        'show_in_menu',
        'user_type',
        'is_special',
        'sort',
        'is_active',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function header(): BelongsTo
    {
        return $this->belongsTo(File::class, 'header_id');
    }

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(CourseCategory::class, 'parent_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Check if course category has any dependencies that would block deletion
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
            'childrenCategories' => 'childrenCategories',
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
                "sectionName" => __("basemodule::message.$relation"),
            ]);
        }
        return null;
    }
}
