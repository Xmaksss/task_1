<?php

require_once __DIR__ . "/../bootstrap.php";

$app = \App\App::instance();

$db = $app->getLib(\App\Libs\DB::class);

$fails = 0;
$reading = true;

while ($reading) {
    if ($fails > 4) {
        $reading = false;
        break;
    }

    $socket = socket_create(AF_INET, SOCK_STREAM, 0);
    $result = socket_connect($socket, '127.0.0.1', env('WORKER_PORT'));

    if (!$result) {
        $fails++;
        sleep(1);
        continue;
    };

    $jobPayload = socket_read ($socket, 1024);

    if (empty($jobPayload))
        continue;

    [$class, $jobData] = json_decode($jobPayload, true);

    echo "Start: $jobPayload \n";

    /** @var \App\Jobs\JobInterface $job */
    $job = new $class($jobData);
    $job->process();

    echo "Finish: $jobPayload \n";

    socket_close($socket);
}