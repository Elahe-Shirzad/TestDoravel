<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\CourseStatus\Models;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CourseStatusAccess
 *
 * @property int $id
 * @property int $course_status_id
 * @property int $child_course_status_id
 * @property Carbon $created_at
 * @property int $created_by
 *
 * @property Admin $admin
 * @property CourseStatus $course_status
 *
 * @package App\Models
 */
class CourseStatusAccess extends Pivot
{
    protected $table = 'course_status_access';
    public $timestamps = false;

    protected $casts = [
        'course_status_id' => 'int',
        'child_course_status_id' => 'int',
        'created_by' => 'int'
    ];

    protected $fillable = [
        'course_status_id',
        'child_course_status_id',
        'created_by'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function courseStatus(): BelongsTo
    {
        return $this->belongsTo(CourseStatus::class);
    }

    public function courseStatusAccess(): BelongsTo
    {
        return $this->belongsTo(CourseStatusAccess::class, 'child_course_status_id');
    }

    public function courseStatusAccesses(): HasMany
    {
        return $this->hasMany(CourseStatusAccess::class, 'child_course_status_id');
    }
}
