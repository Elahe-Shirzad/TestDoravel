<?php

namespace Modules\Bank\Traits;

use Dornica\Foundation\Core\Enums\IsActive;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Dornica\APIKit\Dorapi\Enums\SortDirection;
use Illuminate\Support\Collection;

trait AvailableScopeTrait
{
    private array $tableColumns;

    /**
     * @throws Exception
     */
    protected function initializeAvailableScopeTrait(): void
    {
        if (!($this instanceof Model)) {
            throw new Exception('The [' . __TRAIT__ . '] trait is only usable on eloquent models');
        }

//        TODO[FUTURE]: add this condition to base-pack
        $this->tableColumns = !App::runningInConsole() ? $this->getFillable() : [];
    }

    /**
     * @param $query
     * @return void
     */
    public function scopeActive($query): void
    {
        if (in_array('is_active', $this->tableColumns)) {
            if (array_key_exists('is_active', $this->casts)) {
                if ($this->casts['is_active'] == IsActive::class) {
                    $query->where('is_active', IsActive::YES);
                } elseif ($this->casts['is_active'] == 'bool' || $this->casts['is_active'] == 'boolean') {
                    $query->where('is_active', true);
                } elseif ($this->casts['is_active'] == 'int') {
                    $query->where('is_active', 1);
                }
            }
        }
    }

    /**
     * @param $query
     * @return void
     */
    public function scopeActiveStatus($query): void
    {
        if (in_array('status', $this->tableColumns)) {
            if (array_key_exists('status', $this->casts)) {
                if ($this->casts['status'] == IsActive::class) {
                    $query->where('status', IsActive::YES);
                } elseif ($this->casts['status'] == 'bool' || $this->casts['status'] == 'boolean') {
                    $query->where('status', true);
                } elseif ($this->casts['status'] == 'int') {
                    $query->where('status', 1);
                }
            }
        }
    }

    /**
     * @param $query
     * @return void
     */
    public function scopeDeactive($query): void
    {
        if (in_array('is_active', $this->tableColumns)) {
            if (array_key_exists('is_active', $this->casts)) {
                if ($this->casts['is_active'] == IsActive::class) {
                    $query->where('is_active', IsActive::NO);
                } elseif ($this->casts['is_active'] == 'bool' || $this->casts['is_active'] == 'boolean') {
                    $query->where('is_active', false);
                } elseif ($this->casts['is_active'] == 'int') {
                    $query->where('is_active', 0);
                }
            }
        }
    }

    /**
     * @param $query
     * @return void
     */
    public function scopeSort($query): void
    {
        if (in_array('sort', $this->tableColumns)) {
            $query->orderBy('sort');
        } elseif (in_array('name', $this->tableColumns)) {
            $query->orderBy('name');
        } elseif (in_array('title', $this->tableColumns)) {
            $query->orderBy('title');
        }
    }

    /**
     * @param $query
     * @param bool $only_actives
     * @param bool $apply_sort
     * @return void
     */
    public function scopeDynamicAvailableQuery($query, bool $only_actives = true, bool $apply_sort = true): void
    {
        if ($only_actives) {
            $query->active();
        }
        if ($apply_sort) {
            $query->sort();
        }
    }

    /**
     * @param $query
     * @return void
     */
    public function scopeAvailableQuery($query): void
    {
        $query
            ->active()
            ->sort();
    }

    /**
     * @param $query
     * @return void
     */
    public function scopeAvailableRowsQuery($query): void
    {
        $query
            ->activeStatus()
            ->sort();
    }

    /**
     * @param $query
     * @param bool $apply_sort
     * @return mixed
     */
    public function scopeAvailable($query, bool $apply_sort = true): mixed
    {
        return $query
            ->availableQuery()
            ->get();
    }

    /**
     * @param $query
     * @param bool $apply_sort
     * @return mixed
     */
    public function scopeAvailableRows($query, bool $apply_sort = true): mixed
    {
        return $query
            ->availableRowsQuery()
            ->get();
    }

    /**
     * @param $query
     * @param bool $only_actives
     * @param bool $apply_sort
     * @return mixed
     */
    public function scopeDynamicAvailable($query, bool $only_actives = true, bool $apply_sort = true): mixed
    {
        return $query
            ->dynamicAvailableQuery($only_actives, $apply_sort)
            ->get();
    }

    /**
     * Retrieve a list of available data with optional filters and sorting.
     *
     * This method returns model records based on the given conditions.
     *
     * Features:
     *  - Filter records by active/inactive status (`is_active` field).
     *  - Force include one or multiple specific items, regardless of other filters.
     *  - Sort results by the `sort` column in the specified direction.
     *  - Allow customizing the field used for identifying the item (default: `id`).
     *
     * How it works:
     *  - If `$isActive` is provided, results will be filtered by the given active status.
     *  - If `$selectedSelfItem` is provided (int, model instance, or array):
     *      • If it’s an array → all items matching the IDs in the array will be included.
     *      • If it’s an integer or model → that specific item will be included.
     *  - If `$sort` is provided, the results will be ordered by the `sort` column in the given direction.
     *  - The final output is a `Collection` of model records.
     *
     * @param IsActive|null                    $isActive          Active status filter (default: IsActive::YES)
     * @param self|array<int>|int|null         $selectedSelfItem  Specific item(s) to force include
     * @param SortDirection|null               $sort              Sort direction (ASC or DESC)
     * @param string                           $selfFieldName     Field name used for identifying items (default: id)
     * @param array|null                           $fields     Field selected and return collection records (default: *)
     * @return Collection  Collection of filtered model records
     */
    public static function getAvailableData(
        self|array|int|null  $selectedSelfItem = null,
        ?IsActive      $isActive = IsActive::YES,
        ?SortDirection $sort = SortDirection::ASC,
        string         $selfFieldName = 'id',
        ?array         $fields = null,
    ): Collection
    {
        // handle conditions
        return self::where(function ($query) use ($selectedSelfItem, $isActive, $selfFieldName) {
            // handle is_active (status) condition
            if (!is_null($isActive)) {
                $query->where('is_active', $isActive->value);
            }

            // handle force selected item
            if (!is_null($selectedSelfItem)) {
                // if used array for multi items
                if(is_array($selectedSelfItem)) {
                    $query->orWhereIn($selfFieldName, $selectedSelfItem);
                }
                // else if used self:model or id of item
                else {
                    $query->orWhere($selfFieldName, is_int($selectedSelfItem) ? $selectedSelfItem : $selectedSelfItem->id);
                }
            }
        })->
        // on fill $sort this section working for sort items
        when(!is_null($sort), fn ($query) => $query->orderBy('sort', $sort->value))->
        // select fields
        select($fields ?: '*')->
        // get data
        get();
    }
}
