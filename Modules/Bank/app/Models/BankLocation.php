<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Bank\Models;

use Carbon\Carbon;
use Dornica\Foundation\Core\Enums\IsDeleted;
use Illuminate\Database\Eloquent\Model;
use Dornica\Foundation\Core\Traits\SoftDeletes;

/**
 * Class BankLocation
 *
 * @property int $id
 * @property int $bank_id
 * @property int $location_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $is_deleted
 *
 * @property Bank $bank
 * @property Location $location
 *
 * @package App\Models
 */
class BankLocation extends Model
{
	use SoftDeletes;
	protected $table = 'bank_location';
	public static $snakeAttributes = false;

	protected $casts = [
		'bank_id' => 'int',
		'location_id' => 'int',
		'is_deleted' => IsDeleted::class
	];

	protected $fillable = [
		'bank_id',
		'location_id',
		'is_deleted'
	];

	public function bank()
	{
		return $this->belongsTo(Bank::class);
	}

	public function location()
	{
		return $this->belongsTo(Location::class);
	}
}
