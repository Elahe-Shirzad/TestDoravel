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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Bank\Enums\BankType;
use Modules\Bank\Traits\FormattedDate;

/**
 * Class Bank
 *
 * @property int $id
 * @property int $image_id
 * @property string $name
 * @property string $code
 * @property int $sort
 * @property bool $is_active
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $published_at
 * @property Carbon|null $expired_at
 * @property string|null $description
 * @property int|null $type
 * @property string|null $color
 *
 * @property Collection|Location[] $locations
 *
 * @package App\Models
 */
class Bank extends Model
{
	use SoftDeletes, FormattedDate;
	protected $table = 'banks';
	public static $snakeAttributes = false;


    protected $casts = [
		'sort' => 'int',
		'image_id' => 'int',
		'is_active' => IsActive::class,
		'is_deleted' => 'int',
		'published_at' => 'datetime',
		'expired_at' => 'datetime',
		'type' => BankType::class
	];

	protected $fillable = [
		'name',
		'image_id',
		'code',
		'sort',
		'is_active',
		'is_deleted',
		'published_at',
		'expired_at',
		'description',
		'type',
		'color'
	];

	public function locations()
	{
		return $this->belongsToMany(Location::class)
					->withPivot('id', 'deleted_at', 'is_deleted')
					->withTimestamps();
	}
//    public function locations()
//    {
//        return $this->belongsToMany(Location::class, 'bank_location')
//            ->withPivot('is_deleted');
//    }

    public function image()
    {
        return $this->belongsTo(File::class, 'image_id');
    }


}
