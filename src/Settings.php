<?php

namespace ForTheLocal\Laravel\Config;

class Settings
{

    private static $config;

    private function __construct()
    {
        // none
    }

    public static function loadConfig(string $pathToConfig): void
    {
        self::$config = new Config($pathToConfig);
    }

    public static function config()
    {
        return self::$config;
    }


}