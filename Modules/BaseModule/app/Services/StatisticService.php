<?php

namespace Modules\BaseModule\Services;

use Hekmatinasser\Verta\Verta;
use Hekmatinasser\Verta\VertaInterface;
use Illuminate\Support\Collection;

class StatisticService
{
    // Constants for icons
    public const ICON_UP = 'fa-arrow-trend-up';
    public const ICON_DOWN = 'fa-arrow-trend-down';

    // Constants for UI variants
    public const VARIANT_SUCCESS = 'success';
    public const VARIANT_DANGER = 'danger';
    public const VARIANT_NEUTRAL = 'secondary';

    protected Collection $timeFrames;
    protected Collection $previousTimeFrames;

    public function __construct()
    {
        $this->timeFrames = collect($this->getTimeFrames());
        $this->previousTimeFrames = collect($this->getPreviousTimeFrames());
    }

    /**
     * Define current time frames based on Jalali calendar.
     */
    protected function getTimeFrames(): array
    {
        $now = Verta::now();

        return [
            'today' => $this->createJalaliRange($now),
            'this_week' => $this->createJalaliRange($now, 'startWeek', 'endWeek'),
            'this_month' => $this->createJalaliRange($now, 'startMonth', 'endMonth'),
            'this_year' => $this->createJalaliRange($now, 'startYear', 'endYear'),
            'all' => null,
        ];
    }

    /**
     * Define previous time frames for comparison based on Jalali calendar.
     */
    protected function getPreviousTimeFrames(): array
    {
        return [
            'yesterday' => $this->createJalaliRange(Verta::now()->subDay()),
            'last_week' => $this->createJalaliRange(Verta::now()->subWeek(), 'startWeek', 'endWeek'),
            'last_month' => $this->createJalaliRange(Verta::now()->subMonth(), 'startMonth', 'endMonth'),
            'last_year' => $this->createJalaliRange(Verta::now()->subYear(), 'startYear', 'endYear'),
        ];
    }

    /**
     * Create a Jalali date range from a given Verta date and range type.
     */
    protected function createJalaliRange(Verta $date, string $start = 'startDay', string $end = 'endDay'): array
    {
        return [
            $date->copy()->{$start}()->datetime(),
            $date->copy()->{$end}()->datetime()
        ];
    }

    /**
     * Generate statistics for all defined timeframes.
     */
    public function generate(callable $metricFunction): Collection
    {
        $current = $this->timeFrames->map($metricFunction);
        $previous = $this->previousTimeFrames->map($metricFunction);

        return collect([
            'today' => $this->format($current['today'], $previous['yesterday'] ?? null),
            'this_week' => $this->format($current['this_week'], $previous['last_week'] ?? null),
            'this_month' => $this->format($current['this_month'], $previous['last_month'] ?? null),
            'this_year' => $this->format($current['this_year'], $previous['last_year'] ?? null),
            'all' => $this->format($current['all'], null),
        ]);
    }

    /**
     * Format the output for a given timeframe.
     */
    protected function format(mixed $current, mixed $previous): array
    {
        if (is_array($current)) {
            return collect($current)->mapWithKeys(function ($value, $key) use ($previous) {
                $previousValue = $previous[$key] ?? 0;
                return [$key => $this->buildStat($value, $previousValue)];
            })->toArray();
        }

        return $this->buildStat($current, $previous);
    }

    /**
     * Build the final stat format with count, percent change, icon, and UI variant.
     */
    protected function buildStat(mixed $current, mixed $previous = null): array
    {
        $change = $previous !== null
            ? $this->calculateChange($current, $previous)
            : ['percent' => null, 'icon' => null];

        return [
            'count' => $current,
            ...$change,
            'variant' => $this->mapVariant($change['icon'] ?? null),
        ];
    }

    /**
     * Calculate percent change and determine icon.
     */
    protected function calculateChange(float|int $current, float|int $previous): array
    {
        // Both zero means no change
        if ($current == 0 && $previous == 0) {
            return [
                'percent' => 0,
                'icon' => null,
            ];
        }

        // Current zero but previous had value = -100% decrease
        if ($current == 0 && $previous > 0) {
            return [
                'percent' => 100,
                'icon' => self::ICON_DOWN,
            ];
        }

        // Previous zero but current has value = +100% increase (capped)
        if ($previous == 0 && $current > 0) {
            return [
                'percent' => 100,
                'icon' => self::ICON_UP,
            ];
        }

        // Normal case - calculate percentage change relative to previous value
        $change = ($current - $previous) / $previous;
        $percent = round($change * 100, 2);

        // Cap the percentage between -100% and +100%
        $percent = min(max($percent, -100), 100);

        $icon = match (true) {
            $percent > 0 => self::ICON_UP,
            $percent < 0 => self::ICON_DOWN,
            default => null,
        };

        return [
            'percent' => abs($percent),
            'icon' => $icon,
        ];
    }

    /**
     * Map icon to UI variant.
     */
    protected function mapVariant(?string $icon): string
    {
        return match ($icon) {
            self::ICON_UP => self::VARIANT_SUCCESS,
            self::ICON_DOWN => self::VARIANT_DANGER,
            default => self::VARIANT_NEUTRAL,
        };
    }
}
