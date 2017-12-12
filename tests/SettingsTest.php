<?php

namespace ForTheLocal\Test;

use ForTheLocal\Laravel\Config\Settings;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class SettingsTest extends OrchestraTestCase
{

    public function testLoadConfig()
    {
        $path = __DIR__ . "/fixtures/config";
        Settings::loadConfig($path);
        $this->assertEquals("1", Settings::config()->size);
        $this->assertEquals("google.com", Settings::config()->server);

        $this->assertEquals("3", Settings::config()->section->site);
        $this->assertEquals([["name" => "yahoo.com"], ["name" => "amazon.com"]],
            Settings::config()->section->servers);
    }


}
