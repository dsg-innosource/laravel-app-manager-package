<?php

namespace Dsginnosource\LamPackage;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dsginnosource\LamPackage\Skeleton\SkeletonClass
 */
class LamPackageFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lam-package';
    }
}
