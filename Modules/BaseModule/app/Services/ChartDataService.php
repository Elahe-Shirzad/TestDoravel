<?php

namespace Modules\BaseModule\Services;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ChartDataService
{
    /**
     * Query builder instance for fetching data.
     */
    protected Builder $queryBuilder;

    /**
     * The type of statistics: week, month, year, all.
     */
    protected string $type = 'week';

    /**
     * Allowed types of statistics.
     */
    protected const array ALLOWED_TYPES = ['week', 'month', 'year', 'all'];

    /**
     * Custom query callbacks for aggregation.
     * Example: ['activeUsers' => fn($q) => $q->where('active', true)]
     */
    protected array $queries = [];

    /**
     * Aggregation methods corresponding to queries.
     * Example: ['activeUsers' => 'count', 'ratings' => 'avg:rating']
     */
    protected array $aggregations = [];

    /**
     * Initialize service with Eloquent model or query builder.
     */
    public function __construct(Model|Builder $input)
    {
        $this->queryBuilder = $input instanceof Model ? $input->newQuery() : $input;
    }

    /**
     * Set the type of statistics.
     */
    public function setType(string $type): self
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            throw new InvalidArgumentException(
                "Invalid type '{$type}'. Allowed types: " . implode(', ', self::ALLOWED_TYPES)
            );
        }
        $this->type = $type;
        return $this;
    }

    /**
     * Define custom query callbacks for aggregation.
     */
    public function setQueries(array $queries): self
    {
        $this->queries = $queries;
        return $this;
    }

    /**
     * Define aggregation methods for queries.
     */
    public function setAggregations(array $aggregations): self
    {
        $this->aggregations = $aggregations;
        return $this;
    }

    /**
     * Fetch statistics based on the selected type.
     */
    public function getStats(): array
    {
        return match ($this->type) {
            'week' => $this->getLastDaysStats(6),
            'month' => $this->getLastDaysStats(30),
            'year' => $this->getLastYearStats(),
            'all' => $this->getAllYearsStats(),
            default => throw new InvalidArgumentException("Invalid type '{$this->type}'"),
        };
    }

    /**
     * Fetch statistics for all available periods (week, month, year, all).
     */
    public function getAllPeriodsStats(): array
    {
        return array_reduce(
            self::ALLOWED_TYPES,
            fn($result, $period) => array_merge($result, [$period => $this->setType($period)->getStats()]),
            []
        );
    }

    /**
     * Get statistics for the last N days.
     */
    protected function getLastDaysStats(int $days): array
    {
        $start = Carbon::today()->subDays($days);
        $end = Carbon::today()->endOfDay();
        $dateFormat = 'Y-m-d';
        $persianFormat = 'm/d';
        $groupBy = 'DATE(created_at)';

        return $this->getStatsForPeriod($start, $end, $dateFormat, $persianFormat, $groupBy, 'P1D');
    }

    /**
     * Get statistics for the last 12 Persian months.
     */
    protected function getLastYearStats(): array
    {
        $periods = $this->getPersianMonthPeriods();
        $labels = $persianLabels = [];
        $counts = [];

        if (!empty($this->queries)) {
            foreach ($this->queries as $key => $_) {
                $counts[$key] = [];
            }
        }

        foreach ($periods as $p) {
            $labels[] = $p['label'];
            $persianLabels[] = $p['persianLabel'];

            if (empty($this->queries)) {
                $cnt = (clone $this->queryBuilder)
                    ->whereBetween('created_at', [$p['start'], $p['end']])
                    ->count();

                $counts[] = $cnt;
            } else {
                foreach ($this->queries as $key => $closure) {
                    $query = (clone $this->queryBuilder)->tap($closure);
                    $agg = $this->aggregations[$key] ?? 'count';
                    [$aggFunc, $aggCol] = explode(':', $agg . ':*');
                    $aggExpr = $aggFunc === 'count' ? 'COUNT(*) as aggregate' : "{$aggFunc}({$aggCol}) as aggregate";

                    $raw = $query->selectRaw($aggExpr)
                        ->whereBetween('created_at', [$p['start'], $p['end']])
                        ->pluck('aggregate')
                        ->first();

                    $value = $aggFunc === 'avg' ? round((float)$raw, 2) : ($raw ?? 0);
                    $counts[$key][] = $value;
                }
            }
        }

        return compact('labels', 'persianLabels', 'counts');
    }

    /**
     * Get statistics aggregated by Persian years.
     */
    protected function getAllYearsStats(): array
    {
        $labels = $persianLabels = $counts = [];

        if (empty($this->queries)) {
            $data = (clone $this->queryBuilder)
                ->selectRaw('created_at')
                ->get()
                ->groupBy(fn($row) => verta($row->created_at)->year)
                ->map->count();

            $data = $data->sortKeys();

            foreach ($data as $persianYear => $count) {
                $labels[] = (string)$persianYear;
                $persianLabels[] = $persianYear;
                $counts[] = $count;
            }
        } else {
            foreach ($this->queries as $key => $closure) {
                $query = (clone $this->queryBuilder)->tap($closure);

                $agg = $this->aggregations[$key] ?? 'count';
                [$aggFunc, $aggCol] = explode(':', $agg . ':*');
                $aggExpr = $aggFunc === 'count' ? 'COUNT(*) as aggregate' : "{$aggFunc}($aggCol) as aggregate";

                $columns = array_filter(['created_at', $aggCol !== '*' ? $aggCol : null]);
                $columns = implode(',', $columns);

                $data = $query->selectRaw($columns)
                    ->get()
                    ->groupBy(fn($row) => verta($row->created_at)->year)
                    ->map(fn($rows) => $aggFunc === 'count'
                        ? $rows->count()
                        : round((float)$rows->avg($aggCol), 2));

                $data = $data->sortKeys();

                if (empty($labels)) {
                    $labels = array_map('strval', array_keys($data->toArray()));
                    $persianLabels = $labels;
                }

                $counts[$key] = array_map(fn($year) => $data[$year] ?? 0, $labels);
            }
        }

        return compact('labels', 'persianLabels', 'counts');
    }

    /**
     * Get statistics for a specific period with optional aggregation.
     */
    protected function getStatsForPeriod(
        Carbon $start,
        Carbon $end,
        string $dateFormat,
        string $persianFormat,
        string $groupBy,
        string $interval,
        ?int $maxSteps = null
    ): array {
        $labels = $persianLabels = $counts = [];

        if (empty($this->queries)) {
            $rawData = (clone $this->queryBuilder)
                ->selectRaw("{$groupBy} as period, COUNT(*) as count")
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('period')
                ->orderBy('period')
                ->pluck('count', 'period');

            $period = new DatePeriod(
                $start,
                new DateInterval($interval),
                $maxSteps ? $start->copy()->addMonths($maxSteps) : $end
            );

            foreach ($period as $date) {
                $dateKey = $date->format($dateFormat);
                $labels[] = $dateKey;
                $persianLabels[] = verta($dateKey)->format($persianFormat);
                $counts[] = $rawData[$dateKey] ?? 0;
            }
        } else {
            foreach ($this->queries as $key => $closure) {
                $query = (clone $this->queryBuilder)->tap($closure);
                $agg = $this->aggregations[$key] ?? 'count';
                [$aggFunc, $aggCol] = explode(':', $agg . ':*');
                $aggExpr = $aggFunc === 'count' ? 'COUNT(*) as aggregate' : "{$aggFunc}($aggCol) as aggregate";

                $rawData = $query->selectRaw("{$groupBy} as period, {$aggExpr}")
                    ->whereBetween('created_at', [$start, $end])
                    ->groupBy('period')
                    ->orderBy('period')
                    ->pluck('aggregate', 'period');

                if (empty($labels)) {
                    $period = new DatePeriod(
                        $start,
                        new DateInterval($interval),
                        $maxSteps ? $start->copy()->addMonths($maxSteps) : $end
                    );
                    foreach ($period as $date) {
                        $dateKey = $date->format($dateFormat);
                        $labels[] = $dateKey;
                        $persianLabels[] = verta($dateKey)->format($persianFormat);
                    }
                }

                $counts[$key] = array_map(function ($label) use ($rawData, $aggFunc) {
                    $value = $rawData[$label] ?? 0;
                    return $aggFunc === 'avg' ? round((float)$value, 2) : $value;
                }, $labels);
            }
        }

        return compact('labels', 'persianLabels', 'counts');
    }

    /**
     * Generate Persian month periods for the last 12 months.
     */
    protected function getPersianMonthPeriods(): array
    {
        $periods = [];
        $start = verta()->subYear()->startMonth();
        $end = verta()->endMonth();
        $current = $start->copy();

        while ($current->timestamp <= $end->timestamp) {
            $periods[] = [
                'start' => $current->startMonth()->datetime(),
                'end' => $current->endMonth()->datetime(),
                'label' => $current->year . '-' . $current->month,
                'persianLabel' => $current->format('Y %B'),
            ];

            $current = $current->addMonth();
        }

        return $periods;
    }
}
