<?php

namespace Dsginnosource\LamPackage;

class Composer
{
    public $contents;

    public function __construct($contents = null)
    {
        $this->parseLockFile($contents);
    }

    public static function versions()
    {
        $composer = new self();
        return $composer->contents->toArray();
    }

    public static function version($package = null)
    {
        if ($package) {
            $composer = new self();
            return $composer->contents->get($package);
        }

        return self::versions();
    }

    public function parseLockFile($contents = null)
    {
        $contents = $contents ?? json_decode(file_get_contents(base_path('composer.lock')));

        $this->contents = collect($contents->packages)->keyBy('name')->flatMap(function ($content, $name) {
            return [
                $name => $content->version,
            ];
        });
    }
}
