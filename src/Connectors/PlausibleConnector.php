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


    public $baseUrl;

    public $apiKey;

    public $site_id;

    public function __construct()
    {
        $this->withTokenAuth(token: config(key: 'plausible.api_key'));
        $this->baseUrl = config(key: 'plausible.base_url');
        $this->site_id = config(key: 'plausible.site_id');
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl.'/api/v1/stats';
    }


    /**
     * @param $apiKey
     * @return mixed
     */
    function setApiKey($apiKey)
    {
        $this->apiKey=$apiKey;
        $this->withTokenAuth($apiKey);
        return $this->apiKey;
    }
    /**
     * set custom baseUrl for each request
     * @param string $url
     * @return void
     */
    public function setBaseUrl(string $url)
    {
        $this->baseUrl = $url;
    }

    /**
     * Set custom site id programmatically
     * @param string $site_id
     * @return void
     */
    public function setSiteId(string $site_id)
    {
        $this->site_id = $site_id;
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
    public function defaultQuery(): array
    {
        return [
            'site_id' => $this->site_id,
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
