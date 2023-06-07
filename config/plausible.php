<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Plausible URL
      |--------------------------------------------------------------------------
      |
      |  Your plausible's default URL, compatible with self-hosted and plausible.io
      |
      |
      */
    'base_url' => env(key: 'BASE_URL', default: 'https://plausible.io'),
    /*
      |--------------------------------------------------------------------------
      | Site ID (Domain)
      |--------------------------------------------------------------------------
      |
      | Each request requires a site_id parameter which is the domain of your
      | site as configured in Plausible. If you're unsure, navigate to your
      | site settings in Plausible and grab the value of the domain field.
      |
      |
      */
    'site_id' => env(key: 'PLAUSIBLE_SITE_ID'),

    /*
      |--------------------------------------------------------------------------
      | API Key
      |--------------------------------------------------------------------------
      |
      | Each request must be authenticated with an API key using the Bearer Token
      | method. You can obtain an API key for your account by going to your user
      | settings page plausible.io/settings.
      |
      |
      */
    'api_key' => env(key: 'PLAUSIBLE_API_KEY'),

    /*
      |--------------------------------------------------------------------------
      | Time Periods
      |--------------------------------------------------------------------------
      |
      | The options are identical for each endpoint that supports configurable
      | time periods. Each period is relative to a date parameter. The date
      | should follow the standard ISO-8601 format.
      | 12mo - Last n calendar months relative to date.
      | 6mo - Last n calendar months relative to date.
      | month -  The calendar month that date falls into.
      | 30d - Last n days relative to date.
      | 7d - Last n days relative to date.
      | day - Stats for the full day specified in date.
      | custom - Provide a custom range in the date parameter.
      |
      | @see https://plausible.io/docs/stats-api#time-periods
      |
      */
    'allowed_periods' => [
        '12mo', '6mo', 'month', '30d', '7d', 'day', 'custom'
    ],

    /*
      |--------------------------------------------------------------------------
      | Properties
      |--------------------------------------------------------------------------
      |
      | Each pageview and custom event in our database has some predefined properties
      | associated with it. In other analytics tools, these are often referred to as
      | dimensions as well. Properties can be used for filtering and breaking down
      | your stats to drill into more depth.
      |
      | @see https://plausible.io/docs/stats-api#properties
      |
      */
    'allowed_properties' => [
        'event:name', 'event:page', 'visit:entry_page', 'visit:exit_page', 'visit:source',
        'visit:referrer', 'visit:utm_medium', 'visit:utm_source', 'visit:utm_campaign',
        'visit:utm_content', 'visit:utm_term', 'visit:device', 'visit:browser',
        'visit:browser_version', 'visit:os', 'visit:os_version', 'visit:country',
        'visit:region', 'visit:city'
    ],

    /*
      |--------------------------------------------------------------------------
      | Metrics
      |--------------------------------------------------------------------------
      |
      | Metrics are a variety of measurements made on your plausible website
      | in order to better track its performance and statistics.
      |
      |  visitors - The number of unique visitors.
      |  visits - The number of visits/sessions
      |  pageviews - The number of pageview events
      |  views_per_visit - The number of pageviews divided by the number of visits. Returns a floating point number. currently only supported in Aggregate and Timeseries endpoints.
      |  bounce_rate - Bounce rate percentage
      |  visit_duration - Visit duration in seconds
      |  events - The number of events (pageviews + custom events)
      |
      | @see https://plausible.io/docs/metrics-definitions
      |
      */
    'allowed_metrics' => [
        'default' => [
            'visitors', 'visits', 'pageviews', 'views_per_visit', 'bounce_rate', 'visit_duration', 'events'
        ],

        'time-series' => [
            'visitors', 'visits', 'pageviews', 'views_per_visit', 'bounce_rate', 'visit_duration'
        ],
        'breakdown' => [
            'visitors', 'visits', 'pageviews', 'bounce_rate', 'visit_duration'
        ],
    ],


    /*
      |--------------------------------------------------------------------------
      | Caching
      |--------------------------------------------------------------------------
      |
      | Caching can be incredibly powerful and can speed up an application by
      | relying less on a third-party integration. By default response are
      | cached using Laravel default cache drive. Feel free to change
      | to any drive supported by Laravel.
      |
      | @see https://docs.saloon.dev/digging-deeper/caching-responses
      |
      */
    'cache' => [
        'enabled' => env(key: 'PLAUSIBLE_CACHE', default: true),

        'duration' => env(key: 'PLAUSIBLE_CACHE_DURATION', default: 180),

        'driver' => env(key: 'PLAUSIBLE_CACHE_DRIVER', default: env(key: 'CACHE_DRIVER', default: 'file')),
    ]
];
