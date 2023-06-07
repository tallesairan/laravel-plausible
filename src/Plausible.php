<?php

namespace Airan\Plausible;

use Airan\Plausible\Connectors\PlausibleConnector;
use Airan\Plausible\Requests\GetAggregates;
use Airan\Plausible\Requests\GetBreakDown;
use Airan\Plausible\Requests\GetRealtimeVisitors;
use Airan\Plausible\Requests\GetTimeSeries;

class Plausible
{
    protected PlausibleConnector $connector;

    protected bool $cacheEnabled;

    public function __construct()
    {
        $this->connector = new PlausibleConnector();

        if (!config(key: 'plausible.cache.enabled')) {
            $this->connector->disableCaching();
        };
    }

    /**
     * Set baseUrl on plausible connector
     * @param string $url
     * @return void
     */
    public function setBaseUrl(string $url)
    {
        $this->connector->setBaseUrl(url: $url);
    }

    /**
     * set custom site id from default query on plausible connector
     * @param string $siteId
     * @return void
     */
    public function setSiteId(string $siteId)
    {
        $this->connector->setSiteId(siteId: $siteId);
    }

    /**
     * Set custom api key on plausible connector
     * @param string $apiKey
     * @return void
     */
    public function setApiKey(string $apiKey)
    {
        $this->connector->setApiKey(apiKey: $apiKey);
    }
    public function realtime()
    {
        return $this->connector->send(request: new GetRealtimeVisitors())->body();
    }

    public function aggregates(
        ?string $period = '30d',
        array   $metrics = [],
        bool    $compare = true,
        array   $filters = [],
        ?string $date = null,
    ) {
        $request = new GetAggregates(
            period: $period,
            metrics: $metrics ?: config(key: 'plausible.allowed_metrics.default'),
            compare: $compare,
            filters: $filters,
            date: $date ?: now()->format(format: 'Y-m-d'),
        );

        return $this->connector->send(request: $request)->json(key: 'results');
    }

    public function timeSeries(
        ?string $period = '30d',
        array   $metrics = [],
        string  $interval = 'date',
        array   $filters = [],
        ?string $date = null,
    ) {
        $request = new GetTimeSeries(
            period: $period,
            metrics: $metrics ?: config(key: 'plausible.allowed_metrics.time-series'),
            filters: $filters,
            interval: $interval,
            date: $date ?: now()->format(format: 'Y-m-d'),
        );

        return $this->connector->send(request: $request)->json(key: 'results');
    }

    public function breakdown(
        string  $property = 'event:page',
        ?string $period = '30d',
        ?string $date = null,
        array   $metrics = [],
        int     $limit = 100,
        int     $page = 1,
        array   $filters = [],
    ) {
        $request = new GetBreakDown(
            property: $property,
            period: $period,
            date: $date ?: now()->format(format: 'Y-m-d'),
            metrics: $metrics ?: config(key: 'plausible.allowed_metrics.breakdown'),
            limit: $limit,
            page: $page,
            filters: $filters,
        );

        return $this->connector->send(request: $request)->json(key: 'results');
    }
}
