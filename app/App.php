<?php

namespace App;

/**
 * Class App
 * @package App
 */
final class App
{
    private static $_app;

    private $_libsInstances = [];

    /**
     * @return App
     * @throws \Exception
     */
    public static function instance(): self
    {
        return static::$_app;
    }

    /**
     * @param array $params
     * @return App
     * @throws \Exception
     */
    public static function init(array $params = [])
    {
        if (!empty(static::$_app))
            throw new \Exception("App already initialized!");

        return static::$_app = new static($params);
    }

    /**
     * @param object $lib
     */
    public function addLib(object $lib)
    {
        $this->_libsInstances[get_class($lib)] = $lib;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getLib($name)
    {
        return $this->_libsInstances[$name] ?? null;
    }

    /**
     * App constructor.
     * @param array $params
     */
    private function __construct(array $params = [])
    {
    }

    private function __clone() {}

    private function __wakeup() {}
}