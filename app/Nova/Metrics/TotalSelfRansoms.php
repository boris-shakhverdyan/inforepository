<?php

namespace App\Nova\Metrics;

use App\Models\Sale;
use Carbon\Carbon;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class TotalSelfRansoms extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @return ValueResult
     */
    public function calculate(): ValueResult
    {
        return $this->result(Sale::onlySelfRansoms()->count());
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name(): string
    {
        return 'Всего самовыкупов';
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges(): array
    {
        return [];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     */
    public function cacheFor()
    {
//         return now()->addDay();
         return null;
    }
}
