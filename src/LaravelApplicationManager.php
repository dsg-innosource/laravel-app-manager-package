<?php

namespace InnoSource\LaravelApplicationManager;

class LaravelApplicationManager
{
    public static function setCustomData($callback)
    {
        config(['lam.custom' => $callback()]);
    }
}
