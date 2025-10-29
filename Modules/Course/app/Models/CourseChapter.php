<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Course\Models;

use App\Models\Admin;
use App\Models\Course;
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

/**
 * Class CourseChapter
 *
 * @property int $id
 * @property int $course_id
 * @property string $title
 * @property int|null $total_duration
 * @property string|null $description
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
 * @property Course $course
 * @property Collection|CourseContent[] $course_contents
 *
 * @package App\Models
 */
class CourseChapter extends Model
{
    use SoftDeletes, AvailableScopeTrait, UserActivityTracking, Localized, FormattedDate;
	protected $table = 'course_chapters';

	protected $casts = [
		'course_id' => 'int',
		'time' => 'int',
		'sort' => 'int',
		'is_active' => IsActive::class,
		'created_by' => 'int',
		'updated_by' => 'int',
		'is_deleted' => IsDeleted::class,
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'course_id',
		'title',
		'time',
		'description',
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

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function courseContents()
	{
		return $this->hasMany(CourseContent::class);
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
            'courseContents' => 'courseContents',
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

    /**
     * @return string
     */
    public function getFilterTitleAttribute(): string
    {
        // TODO:: check use Attribute
        return "شناسه فصل : {$this->id} - عنوان فصل : {$this->title}";
    }

}
