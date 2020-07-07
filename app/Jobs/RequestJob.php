<?php

namespace App\Jobs;

use App\Models\Job;

class RequestJob implements JobInterface
{
    public $jobId;

    public function __construct(int $jobId)
    {
        $this->jobId = $jobId;
    }

    public function process()
    {
        $model = Job::first(['id' => $this->jobId]);
        if (!$model) return false;
        $model->status = Job::STATUS_PROCESSING;
        $model->save();

        $ch = curl_init($model->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_exec($ch);
        $errno = curl_errno($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($errno != CURLE_OK) {
            $model->status = Job::STATUS_ERROR;
        } else {
            $model->status = Job::STATUS_DONE;
            $model->http_code = $http_code;
        }

        curl_close($ch);

        return $model->save();
    }
}