<?php

namespace Modules\CourseLevel\Models;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CourseLevel\Database\Factories\CourseLevelFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\BaseModule\Traits\AvailableScopeTrait;

/**
 * Class CourseLevel
 *
 * @property int $id
 * @property string $code
 * @property string $title
 * @property string|null $description
 * @property int $sort
 * @property bool $is_active
 * @property Carbon $created_at
 * @property int $created_by
 * @property Carbon $updated_at
 * @property int $updated_by
 * @property bool $is_deleted
 * @property int|null $deleted_by
 * @property string|null $deleted_at
 *
 * @property Admin $admin
 *
 * @package App\Models
 */
class CourseLevel extends Model
{
    use SoftDeletes ,AvailableScopeTrait;

    protected $table = 'course_levels';

    protected $casts = [
        'sort' => 'int',
        'is_active' => 'bool',
        'created_by' => 'int',
        'updated_by' => 'int',
        'is_deleted' => 'bool',
        'deleted_by' => 'int'
    ];

    protected $fillable = [
        'code',
        'title',
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
}
