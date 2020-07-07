<?php

namespace App\Distributors;

use App\Jobs\RequestJob;
use App\Models\Job;

/**
 * Class RequestJobDistributor
 * @package App\Distributors
 */
class RequestJobDistributor implements DistributorInterface {

    public function getJobs(): array
    {
        $jobs = Job::get(['status' => Job::STATUS_NEW]);

        return array_map(function(Job $job) {
            return $job->id;
        }, $jobs);
    }

    public function getJobClass(): string
    {
        return RequestJob::class;
    }
}