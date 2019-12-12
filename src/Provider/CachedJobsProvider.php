<?php

declare(strict_types=1);

namespace App\Provider;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CachedJobsProvider implements JobsProviderInterface, CacheProviderInterface
{
    private JobsProviderInterface $decoratedJobsProvider;

    private CacheInterface $cache;

    private bool $useCache = true;

    public function __construct(JobsProviderInterface $decoratedJobsProvider, CacheInterface $cache)
    {
        $this->decoratedJobsProvider = $decoratedJobsProvider;
        $this->cache = $cache;
    }

    public function getJobs(): array
    {
        return $this->cache->get('spotify_jobs'.$this->getCacheSuffix(), function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->decoratedJobsProvider->getJobs();
        });
    }

    public function getJobsDescription(string $jobUrl): string
    {
        return $this->cache->get('spotify_jobs_descriptions'.md5($jobUrl).$this->getCacheSuffix(), function (ItemInterface $item) use ($jobUrl) {
            $item->expiresAfter(3600);

            return $this->decoratedJobsProvider->getJobsDescription($jobUrl);
        });
    }

    public function useCache(bool $useCache = true): void
    {
        $this->useCache = $useCache;
    }

    private function getCacheSuffix(): string
    {
        if (!$this->useCache) {
            return time().microtime();
        }

        return '';
    }
}
