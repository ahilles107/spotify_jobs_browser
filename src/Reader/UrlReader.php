<?php

declare(strict_types=1);

namespace App\Reader;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UrlReader implements UrlReaderInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(HttpClient::create(['timeout' => 60]));
    }

    public function readWebsite(string $url): Crawler
    {
        return $this->client->request('GET', $url);
    }

    public function readAPI(string $method, string $url, array $options): ResponseInterface
    {
        return HttpClient::create(['timeout' => 60])->request($method, $url, $options);
    }
}
