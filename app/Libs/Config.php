<?php

namespace App\Libs;

class Config
{
    public function __construct(string $configPath)
    {
        if (!file_exists($configPath)) {
            throw new \Exception("Config file '$configPath' not found!");
        }

        $fileContent = file_get_contents($configPath);
        $params = explode("\n", $fileContent);

        foreach ($params as $param) {
            if (!empty($param))
                putenv($param);
        }
    }
}