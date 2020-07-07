<?php

require_once __DIR__ . "/../bootstrap.php";

$distributorProcessor = new \App\Distributors\DistributorProcessor(
    env('WORKER_PORT'),
    env('MAX_CONNECTIONS')
);

$distributorProcessor->loadDistributors([
    \App\Distributors\RequestJobDistributor::class
]);

$distributorProcessor->listen();