<?php

declare(strict_types=1);

namespace App\Provider;

interface JobsProviderInterface
{
    public function getJobs(): array;

    public function getJobsDescription(string $jobUrl): string;
}
