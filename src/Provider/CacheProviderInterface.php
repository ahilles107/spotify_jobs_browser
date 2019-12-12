<?php

declare(strict_types=1);

namespace App\Provider;

interface CacheProviderInterface
{
    public function useCache(bool $useCache = true): void;
}
