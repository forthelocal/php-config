<?php

namespace ForTheLocal\PHPConfig;

use Symfony\Component\Yaml\Yaml;

class Config
{

    private $yml;
    private $ymlStr;
    private $level;
    private $ancestors;

    /**
     * Config constructor.
     * @param string $pathToRoot
     */
    public function __construct(string $pathToRoot)
    {
        $this->ymlStr = file_get_contents($pathToRoot . "/settings.yml");

        $default = Yaml::parse($this->replacePHPCode($this->ymlStr));
        $env = getenv("APP_ENV");

        $envArray = [];
        $envFilePath = $pathToRoot . "/settings/" . $env . ".yml";
        if (!empty($env) && file_exists($envFilePath)) {
            $envYmlStr = file_get_contents($envFilePath);
            if (!empty($envYmlStr)) {
                $envArray = Yaml::parse($this->replacePHPCode($envYmlStr));
            }
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

        if (!array_key_exists($name, $yml)) {
            throw new \Exception("$name does not exist in the yaml below\n======\n" . $this->ymlStr . "\n=======\n");
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

    private function replacePHPCode(string $ymlStr)
    {
        $str = preg_replace_callback('/\{\{\ .+? \}\}/', function ($matches) {
            $str = substr($matches[0], 0, -2);
            $code = 'return ' . trim(substr($str, 2)) . ';';
            return eval($code);
        }, $ymlStr);

        return $str;
    }


}