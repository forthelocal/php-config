<?php

namespace ForTheLocal\Laravel\Config;

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
        $this->yml = Yaml::parse($ymlStr);
        $this->level = 0;
        $this->ancestors = [];
    }

    public function __get(string $name)
    {

        var_dump($this->yml);
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