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
        putenv("APP_NAME");
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

        $this->assertEquals('level2_1_1', Settings::config()->level1->level2_1->level2_1_1);
        $this->assertEquals('level2_2', Settings::config()->level1->level2_2);
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

    public function testEmptyYml()
    {
        $path = __DIR__ . "/fixtures/config";
        putenv("APP_ENV=empty");

        Settings::loadConfig($path);

        $this->assertEquals("1", Settings::config()->size);
    }

    public function testSpecifyingNonexistentNodeThrowsException()
    {
        $path = __DIR__ . "/fixtures/config";
        Settings::loadConfig($path);

        $this->expectException(\Exception::class);
        Settings::config()->nonexistent_node;
    }

    public function testEnv()
    {
        putenv("APP_NAME=test1");
        $path2 = __DIR__ . "/fixtures/config2";
        Settings::loadConfig($path2);
        $this->assertEquals(3, Settings::config()->other);

        $path1 = __DIR__ . "/fixtures/config";
        Settings::loadConfig($path1, 'test2');
        $this->assertEquals(1, Settings::config('test2')->size);
    }

    public function testEnvFileNotExists()
    {
        putenv("APP_ENV=notexists");

        $path = __DIR__ . "/fixtures/config";
        Settings::loadConfig($path);
        $this->assertEquals(1, Settings::config()->size);
    }

}
