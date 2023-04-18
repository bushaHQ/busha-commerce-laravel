<?php

namespace Busha\Commerce\Facades;

use Illuminate\Support\Facades\Facade;

class BushaCommerce extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'busha-commerce';
    }
}