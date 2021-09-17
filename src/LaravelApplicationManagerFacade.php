<?php

namespace InnoSource\LaravelApplicationManager;

use Illuminate\Support\Facades\Facade;

/**
 * @see \InnoSource\LaravelApplicationManager
 */
class LaravelApplicationManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lam';
    }
}
