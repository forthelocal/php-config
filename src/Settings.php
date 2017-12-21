<?php

namespace ForTheLocal\PHPConfig;

class Settings
{

    private static $configs;

    private function __construct()
    {
        // none
    }

    public static function loadConfig(string $pathToConfig, string $namespace = null): void
    {
        if ($namespace == null){
            $namespace = env('APP_NAME', 'default');
        }
        self::$configs[$namespace] = new Config($pathToConfig);
    }

    public static function config(string $namespace = null)
    {
        if ($namespace == null){
            $namespace = env('APP_NAME', 'default');
        }
        return self::$configs[$namespace];
    }


}