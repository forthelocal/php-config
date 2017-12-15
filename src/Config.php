<?php

namespace ForTheLocal\PHPConfig;

use Symfony\Component\Yaml\Yaml;

class Config
{

    private $yml;
    private $level;
    private $ancestors;

    /**
     * Config constructor.
     * @param string $pathToRoot
     */
    public function __construct(string $pathToRoot)
    {
        $ymlStr = file_get_contents($pathToRoot . "/settings.yml");

        $str = preg_replace_callback('/\{\{\ .+? }\}/', function($matches) {
            $str = substr($matches[0],0,-2);
            $code = 'return ' . substr($str,2);
            return eval($code);
        }, $ymlStr);

        $default = Yaml::parse($str);
        $env = getenv("APP_ENV");
        $envArray = [];
        if (!empty($env)) {
            $envYmlStr = file_get_contents($pathToRoot . "/settings/" . $env . ".yml");
            $envArray = Yaml::parse($envYmlStr);
        }

        $merged = array_merge($default, $envArray);

        $this->yml = $merged;
        $this->level = 0;
        $this->ancestors = [];
    }

    public function __get(string $name)
    {
        $yml = $this->yml;
        for ($i = 0; $i < $this->level; $i++) {
            $yml = $yml[$this->ancestors[$i]];
        }

        $var = $yml[$name];

        if (!$this->isHash($var)) {
            $this->level = 0;
            $this->ancestors = [];
            return $var;
        }

        $this->level += 1;
        $this->ancestors[] = $name;

        return $this;
    }

    private function isHash($array)
    {
        if (!is_array($array)) {
            return false;
        }

        $i = 0;
        foreach ($array as $k => $dummy) {
            if ($k !== $i++) return true;
        }
        return false;

    }


}