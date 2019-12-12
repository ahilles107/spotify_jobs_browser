<?php

declare(strict_types=1);

namespace App\Reader;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface UrlReaderInterface
{
    public function readWebsite(string $url): Crawler;

    public function readAPI(string $method, string $url, array $options): ResponseInterface;
}
