<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Course\Database\Factories\CourseFactory;

use App\Models\Admin;
use App\Models\CourseChapter;
use App\Models\CourseFavorite;
use App\Models\File;
use App\Models\Log;
use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsArchived;
use Dornica\Foundation\Core\Enums\IsDeleted;
use Dornica\Foundation\Core\Traits\SoftDeletes;
use Dornica\Foundation\Core\Traits\UserActivityTracking;
use Dornica\Foundation\Localization\Localized;
use Illuminate\Database\Eloquent\Collection;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\BaseModule\Enums\General\BooleanState;
use Modules\BaseModule\Enums\General\CommentStatus;
use Modules\BaseModule\Enums\General\SeoRobot;
use Modules\BaseModule\Enums\General\UserType;
use Modules\BaseModule\Traits\AvailableScopeTrait;
use Modules\BaseModule\Traits\FormattedDate;
use Modules\CourseCategory\Models\CourseCategory;
use Modules\CourseLevel\Models\CourseLevel;
use Modules\CourseStatus\Models\CourseStatus;
use Modules\Instructor\Models\Instructor;

/**
 * Class Course
 *
 * @property int $id
 * @property int $course_category_id
 * @property int|null $image_id
 * @property int|null $introduction_video_file_id
 * @property int|null $instructor_id
 * @property int|null $course_level_id
 * @property int|null $cover_image
 * @property string $title
 * @property string $slug
 * @property int $total_duration
 * @property int $total_course_content
 * @property string $small_description
 * @property string|null $description
 * @property int $star_count
 * @property int $star_sum
 * @property int $view_count
 * @property int $comment_status
 * @property bool $can_view_comment
 * @property Carbon|null $started_at
 * @property Carbon|null $end_at
 * @property int $user_type
 * @property bool $is_special
 * @property int $sort
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property bool|null $seo_robots
 * @property string|null $seo_keywords
 * @property int $course_status_id
 * @property Carbon $created_at
 * @property int $created_by
 * @property Carbon $updated_at
 * @property int $updated_by
 * @property bool $is_archived
 * @property Carbon|null $archived_at
 * @property int|null $archived_by
 * @property bool $is_deleted
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property CourseCategory $course_category
 * @property Admin|null $admin
 * @property CourseStatus $course_status
 * @property File|null $file
 * @property Instructor|null $instructor
 * @property CourseLevel|null $course_level
 * @property Collection|CourseChapter[] $course_chapters
 * @property Collection|CourseComment[] $course_comments
 * @property Collection|CourseContent[] $course_contents
 * @property Collection|CourseFavorite[] $course_favorites
 * @property Collection|Instructor[] $instructors
 * @property Collection|CourseLog[] $course_logs
 * @property Collection|CourseMember[] $course_members
 * @property Collection|CourseRate[] $course_rates
 * @property Collection|CourseReaction[] $course_reactions
 * @property Collection|CourseView[] $course_views
 *
 * @package App\Models
 */
class Course extends Model
{
    use SoftDeletes, AvailableScopeTrait, UserActivityTracking, Localized, FormattedDate;


    protected $table = 'courses';

    protected $casts = [
        'course_category_id' => 'int',
        'image_id' => 'int',
        'introduction_video_file_id' => 'int',
        'instructor_id' => 'int',
        'course_level_id' => 'int',
        'cover_image' => 'int',
        'total_duration' => 'int',
        'total_course_content' => 'int',
        'star_count' => 'int',
        'star_sum' => 'int',
        'view_count' => 'int',
        'comment_status' => CommentStatus::class,
        'can_view_comment' => BooleanState::class,
        'started_at' => 'datetime',
        'end_at' => 'datetime',
        'user_type' => UserType::class,
        'is_special' => BooleanState::class,
        'sort' => 'int',
        'seo_robots' => SeoRobot::class,
        'course_status_id' => 'int',
        'created_by' => 'int',
        'updated_by' => 'int',
        'is_archived' => IsArchived::class,
        'archived_at' => 'datetime',
        'archived_by' => 'int',
        'is_deleted' => IsDeleted::class,
        'deleted_by' => 'int'
    ];

    protected $fillable = [
        'course_category_id',
        'image_id',
        'introduction_video_file_id',
        'instructor_id',
        'course_level_id',
        'cover_image',
        'title',
        'slug',
        'total_duration',
        'total_course_content',
        'small_description',
        'description',
        'star_count',
        'star_sum',
        'view_count',
        'comment_status',
        'can_view_comment',
        'started_at',
        'end_at',
        'user_type',
        'is_special',
        'sort',
        'seo_title',
        'seo_description',
        'seo_robots',
        'seo_keywords',
        'course_status_id',
        'created_by',
        'updated_by',
        'is_archived',
        'archived_at',
        'archived_by',
        'is_deleted',
        'deleted_by'
    ];

    public function courseCategory(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'archived_by');
    }

    public function courseStatus()
    {
        return $this->belongsTo(CourseStatus::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'introduction_video_file_id');
    }

    public function instructor()
    {
        return $this->belongsTo(related: Instructor::class);
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function courseChapters()
    {
        return $this->hasMany(CourseChapter::class);
    }

    public function courseComments()
    {
        return $this->hasMany(CourseComment::class);
    }

    public function courseContents()
    {
        return $this->hasMany(CourseContent::class);
    }

    public function courseFavorites()
    {
        return $this->hasMany(CourseFavorite::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class)
            ->withPivot('id', 'sort', 'is_active', 'created_by', 'updated_by', 'is_deleted', 'deleted_at', 'deleted_by')
            ->withTimestamps();
    }

    public function courseLogs()
    {
        return $this->hasMany(CourseLog::class);
    }

    public function courseMembers()
    {
        return $this->hasMany(CourseMember::class);
    }

    public function courseRates()
    {
        return $this->hasMany(CourseRate::class);
    }

    public function courseReactions()
    {
        return $this->hasMany(CourseReaction::class);
    }

    public function courseViews()
    {
        return $this->hasMany(CourseView::class);
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'entity');
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
            'courseViews' => 'courseViews',
            'courseRates' => 'courseRates',
            'courseMembers' => 'courseMembers',
            'courseChapters' => 'courseChapters',
            'courseComments' => 'courseComments',
            'courseContents' => 'courseContents',
            'courseFavorites' => 'courseFavorites',
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
                "sectionName" => __("course::message.$relation"),
            ]);
        }
        return null;
    }
}
