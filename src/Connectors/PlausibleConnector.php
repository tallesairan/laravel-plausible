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
    public function __construct()
    {
        $this->withTokenAuth(token: config(key: 'plausible.api_key'));
        $this->baseUrl = config(key: 'plausible.base_url');
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

    public function defaultConfig(): array
    {
        return [
            'timeout' => 150,
        ];
    }

    protected function defaultQuery(): array
    {
        return [
            'site_id' => config(key: 'plausible.site_id'),
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
