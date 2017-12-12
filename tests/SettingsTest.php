<?php

namespace ForTheLocal\Test;

use ForTheLocal\Laravel\Config\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{

    public function testLoadConfig()
    {
        $path = __DIR__ . "/fixtures/config";
        Settings::loadConfig($path);
        $this->assertEquals("1", Settings::config()->size);
        $this->assertEquals("default.google.com", Settings::config()->server);

        $this->assertEquals("3", Settings::config()->section->site);
        $this->assertEquals([["name" => "default.yahoo.com"], ["name" => "default.amazon.com"]],
            Settings::config()->section->servers);
    }


}
