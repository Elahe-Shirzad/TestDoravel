<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Bank\Models;
use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\Foundation\Core\Traits\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Bank\Traits\FormattedDate;
use Modules\Location\Enums\Service;


/**
 * Class Location
 *
 * @property int $id
 * @property string $square
 * @property string|null $street
 * @property string|null $alley
 * @property string $branch
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * @property int|null $is_deleted
 * @property int $sort
 * @property int $is_active
 * @property int|null $avatar_id
 * @property string|null $color
 * @property string|null $description
 * @property string $full_address
 * @property Carbon|null $published_at
 * @property Carbon|null $expired_at
 * @property int $service
 *
 * @property Collection|Bank[] $banks
 *
 * @package App\Models
 */
class Location extends Model
{
	use SoftDeletes, FormattedDate;
	protected $table = 'locations';
	public static $snakeAttributes = false;

    protected $appends = ['full_name'];

	protected $casts = [
		'is_deleted' => 'int',
		'sort' => 'int',
		'is_active' => IsActive::class,
		'avatar_id' => 'int',
		'published_at' => 'datetime',
		'expired_at' => 'datetime',
		'service' => Service::class
	];

	protected $fillable = [
		'square',
		'street',
		'alley',
		'branch',
		'is_deleted',
		'sort',
		'is_active',
		'avatar_id',
		'color',
		'description',
		'full_address',
		'published_at',
		'expired_at',
		'service'
	];

	public function banks()
	{
		return $this->belongsToMany(Bank::class)
					->withPivot('id', 'deleted_at', 'is_deleted')
					->withTimestamps();
	}

    public function getFullNameAttribute(): string
    {
        return "شعبه{$this->branch}
        -
         میدان {$this->square}";
    }


    public function avatar()
    {
        return $this->belongsTo(File::class, 'avatar_id');
    }
}
