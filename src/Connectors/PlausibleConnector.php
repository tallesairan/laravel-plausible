<?php

namespace Airan\Plausible\Connectors;

use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Http\Connector;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Contracts\Cacheable;

class PlausibleConnector extends Connector implements Cacheable
{
    use HasCaching;


    protected $baseUrl;

    protected $siteId;
    public function __construct()
    {
        $this->withTokenAuth(token: config(key: 'plausible.api_key'));
        $this->baseUrl = config(key: 'plausible.base_url');
        $this->siteId = config(key: 'plausible.site_id');
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl.'/api/v1/stats';
    }

    /**
     * set custom baseUrl for each request
     * @param string $url
     * @return void
     */
    function setBaseUrl(string $url)
    {
        $this->baseUrl = $url;
    }

    /**
     * Set custom site id programmatically
     * @param string $siteId
     * @return void
     */
    public function setSiteId(string $siteId)
    {
        $this->siteId = $siteId;
    }
    public function defaultConfig(): array
    {
        return [
            'timeout' => 150,
        ];
    }

    /**
     * Change default query for custom site_id
     * @return array
     */
    protected function defaultQuery(): array
    {
        return [
            'site_id' => $this->siteId,
        ];
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(
            store: Cache::store(config(key: 'plausible.cache.driver'))
        );
    }

    public function cacheExpiryInSeconds(): int
    {
        return config(key: 'plausible.cache.duration');
    }
}
