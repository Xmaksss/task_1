<?php

return [
    'driver' => env('DB_DRIVER', 'sqlite'),
    'host' => env('DB_HOST', 'db.sqlite'),
    'port' => env('DB_PORT'),
    'user' => env('DB_USER'),
    'name' => env('DB_NAME'),
    'password' => env('DB_PASSWORD'),

    'migrations' => [
        CreateJobsTable::class
    ]
];