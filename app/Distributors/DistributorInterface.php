<?php

namespace App\Distributors;

interface DistributorInterface
{
    public function getJobClass(): string;
    public function getJobs(): array;
}