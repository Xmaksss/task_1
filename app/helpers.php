<?php

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return getenv($key) ?? $default;
    }
}

if (!function_exists('root_path')) {
    function root_path($path = '')
    {
        return __DIR__ . '/../' . $path;
    }
}