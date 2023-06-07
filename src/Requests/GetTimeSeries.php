<?php

namespace Airan\Plausible\Requests;

use RuntimeException;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetTimeSeries extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public string $period = '30d',
        public array $metrics = [],
        public array $filters = [],
        public string $interval = 'date',
        public ?string $date = null,
    ) {
        if (! in_array(needle: $this->interval, haystack: ['date','month'])) {
            throw new RuntimeException(message: trans(key: 'plausible::plausible.invalid_interval'));
        }

        if (! in_array(needle: $this->period, haystack: config(key: 'plausible.allowed_periods'))) {
            throw new RuntimeException(message: trans(key: 'plausible::plausible.invalid_period'));
        }

        if (count(value: array_diff($this->metrics, config(key: 'plausible.allowed_metrics.time-series'))) !== 0) {
            throw new RuntimeException(message: trans(key: 'plausible::plausible.invalid_metric'));
        }
    }

    public function resolveEndpoint(): string
    {
        return '/timeseries';
    }

    protected function defaultQuery(): array
    {
        $query = [
            'period'  => $this->period,
            'date'    => $this->date,
            'metrics' => $this->getMetrics(),
        ];

        if (!empty($this->filters)) {
            $query['filters'] = $this->getFilters();
        }

        if ($this->interval) {
            $query['$interval'] = $this->interval;
        }

        return $query;
    }

    protected function getMetrics(): string
    {
        return implode(separator: ',', array: $this->metrics);
    }

    protected function getFilters(): string
    {
        return implode(separator: ';', array: $this->filters);
    }
}
