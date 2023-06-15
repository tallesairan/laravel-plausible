<?php

namespace Airan\Plausible;

use Airan\Plausible\Connectors\PlausibleConnector;
use Airan\Plausible\Exception\PlausibleException;
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
     * @param string $site_id
     * @param bool $removeWWW remove www. from domain
     * @return void
     */
    public function setSiteId(string $site_id, bool $removeWWW = false)
    {
        if($removeWWW){
            $site_id = str_replace(search: 'www.', replace: '', subject: $site_id);
        }
        $this->connector->setSiteId(site_id: $site_id);
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
            site_id: $this->connector->site_id,

            period: $period,
            metrics: $metrics ?: config(key: 'plausible.allowed_metrics.default'),
            compare: $compare,
            filters: $filters,
            date: $date ?: now()->format(format: 'Y-m-d'),
        );
        $aggregates = $this->connector->send(request: $request)->json();
        if($aggregates){
            if(isset($aggregates['error'])){
                throw new PlausibleException(message: $aggregates['error'], data: [
                    'method'=>$request->getMethod(),
                    'endpoint'=>$request->resolveEndpoint(),
                    'site_id'=>$this->connector->site_id,
                    ''
                ]);

            }

            return $aggregates['results'];
        }
        throw new PlausibleException(message: 'Error on Request');

    }

    public function timeSeries(
        ?string $period = '30d',
        array   $metrics = [],
        string  $interval = 'date',
        array   $filters = [],
        ?string $date = null,
    ) {

        $request = new GetTimeSeries(
            site_id: $this->connector->site_id,
            period: $period,
            metrics: $metrics ?: config(key: 'plausible.allowed_metrics.time-series'),
            filters: $filters,
            interval: $interval,
            date: $date ?: now()->format(format: 'Y-m-d'),
        );
        $timeSeriesResponse = $this->connector->send(request: $request);


        $timeSeriesResponse=$timeSeriesResponse->json();
        if($timeSeriesResponse){
            if(isset($timeSeriesResponse['error'])){
                throw new PlausibleException(message: $timeSeriesResponse['error'],data: [
                    'method'=>$request->getMethod(),
                    'endpoint'=>$request->resolveEndpoint(),
                    'site_id'=>$this->connector->site_id,
                    ''
                ]);
            }

            return $timeSeriesResponse['results'];
        }
        throw new PlausibleException(message: 'Error on Request');
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
            site_id: $this->connector->site_id,

            property: $property,
            period: $period,
            date: $date ?: now()->format(format: 'Y-m-d'),
            metrics: $metrics ?: config(key: 'plausible.allowed_metrics.breakdown'),
            limit: $limit,
            page: $page,
            filters: $filters,
        );
        $breakdown = $this->connector->send(request: $request)->json();
        if($breakdown){
            if(isset($breakdown['error'])){

                throw new PlausibleException(message: $breakdown['error'], data: [
                    'method'=>$request->getMethod(),
                    'endpoint'=>$request->resolveEndpoint(),
                    'site_id'=>$this->connector->site_id,
                    ''
                ]);
            }

            return $breakdown['results'];
        }
        throw new PlausibleException(message: 'Error on Request');
    }
}
