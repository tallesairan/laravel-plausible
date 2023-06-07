<?php

namespace Airan\Plausible\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Airan\Plausible\Plausible
 */
class Plausible extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Airan\Plausible\Plausible::class;
    }
}
