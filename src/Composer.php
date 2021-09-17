<?php

namespace InnoSource\LaravelApplicationManager;

class Composer
{
    public $contents;
    public $require;
    public $requireDev;

    public function __construct($contents = null)
    {
        $this->parseLockFile($contents);
        $this->parseJsonFile($contents);
    }

    public static function versions()
    {
        $composer = new self();
        return $composer->contents->toArray();
    }

    public static function required()
    {
        $composer = new self();
        return $composer->require->toArray();
    }

    public static function requiredDev()
    {
        $composer = new self();
        return $composer->requireDev->toArray();
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

        $this->contents = collect($contents->packages)
            ->keyBy('name')
            ->flatMap(function ($content, $name) {
                return [
                    $name => $content->version,
                ];
            });
    }

    public function parseJsonFile($contents = null)
    {
        $contents = $contents ?? json_decode(file_get_contents(base_path('composer.json')));

        $this->require = collect($contents->require);
        $this->requireDev = collect($contents->{'require-dev'});
    }
}
