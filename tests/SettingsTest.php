<?php

namespace ForTheLocal\Test;

use ForTheLocal\PHPConfig\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{

    public function tearDown()
    {
        parent::tearDown();
        putenv("APP_ENV");
    }



    public function testDefaultConfig()
    {
        $path = __DIR__ . "/fixtures/config";
        Settings::loadConfig($path);
        $this->assertEquals("1", Settings::config()->size);
        $this->assertEquals("default.google.com", Settings::config()->server);

        $this->assertEquals("3", Settings::config()->section->site);
        $this->assertEquals([["name" => "default.yahoo.com"], ["name" => "default.amazon.com"]],
            Settings::config()->section->servers);
    }

    public function testEnvironmentalConfig()
    {
        $path = __DIR__ . "/fixtures/config";
        putenv("APP_ENV=development");

        Settings::loadConfig($path);
        $this->assertEquals("1", Settings::config()->size);
        $this->assertEquals("development.google.com", Settings::config()->server);

        $this->assertEquals("4", Settings::config()->section->site);
        $this->assertEquals([["name" => "development.yahoo.com"], ["name" => "development.amazon.com"]],
            Settings::config()->section->servers);
    }

    public function testPHPCode()
    {

        $envStr1 = 'phpcodetest1';
        $envStr2 = 'phpcodetest2';
        putenv("PHP_CONFIG_ENV1=$envStr1");
        putenv("PHP_CONFIG_ENV2=$envStr2");

        $path = __DIR__ . "/fixtures/config";
        Settings::loadConfig($path);

        $this->assertEquals($envStr1, Settings::config()->php_code->env1);
        $this->assertEquals($envStr2, Settings::config()->php_code->env2);
    }

}
