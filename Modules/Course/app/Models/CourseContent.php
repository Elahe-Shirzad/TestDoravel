<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Course\Models;

use App\Models\Admin;
use App\Models\CourseChapter;
use App\Models\CourseFile;
use App\Models\File;
use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\Foundation\Core\Enums\IsDeleted;
use Dornica\Foundation\Core\Traits\SoftDeletes;
use Dornica\Foundation\Core\Traits\UserActivityTracking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Enums\General\SeoRobot;
use Modules\BaseModule\Enums\General\UserType;
use Modules\BaseModule\Traits\FormattedDate;
use Modules\Course\Enums\CourseContentFileType;

/**
 * Class CourseContent
 *
 * @property int $id
 * @property int $course_id
 * @property int $course_chapter_id
 * @property int|null $cover_image
 * @property int|null $file_id
 * @property string $title
 * @property string $slug
 * @property string|null $style
 * @property string $total_duration
 * @property string|null $description
 * @property bool $is_special
 * @property int $user_type
 * @property int $sort
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property bool|null $seo_robots
 * @property string|null $seo_keywords
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
 * @property CourseChapter $course_chapter
 * @property File|null $file
 * @property Course $course
 * @property Collection|CourseFile[] $course_files
 *
 * @package App\Models
 */
class CourseContent extends Model
{
    use SoftDeletes,
        FormattedDate,
        UserActivityTracking;

    protected $table = 'course_contents';

    protected $casts = [
        'course_id' => 'int',
        'course_chapter_id' => 'int',
        'cover_image' => 'int',
        'file_id' => 'int',
        'is_special' => BooleanState::class,
        'user_type' => UserType::class,
        'type' => CourseContentFileType::class,
        'sort' => 'int',
        'seo_robots' => SeoRobot::class,
        'is_active' => IsActive::class,
        'created_by' => 'int',
        'updated_by' => 'int',
        'is_deleted' => IsDeleted::class,
        'deleted_by' => 'int'
    ];

    protected $fillable = [
        'course_id',
        'course_chapter_id',
        'cover_image',
        'file_id',
        'title',
        'slug',
        'style',
        'total_duration',
        'description',
        'is_special',
        'user_type',
        'type',
        'sort',
        'seo_title',
        'seo_description',
        'seo_robots',
        'seo_keywords',
        'is_active',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by'
    ];

    /**
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * @return BelongsTo
     */
    public function courseChapter(): BelongsTo
    {
        return $this->belongsTo(CourseChapter::class);
    }

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    /**
     * @return BelongsTo
     */
    public function cover(): BelongsTo
    {
        return $this->belongsTo(File::class, 'cover_image');
    }

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    /**
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
