<?php

namespace App\Tests;

use App\Provider\SpotifyJobsProvider;
use App\Reader\UrlReaderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpotifyJobsProviderTest extends TestCase
{
    public function testGettingJobs()
    {
        $jobsProvider = new SpotifyJobsProvider(new class() implements UrlReaderInterface {
            public function readAPI(string $method, string $url, array $options): ResponseInterface
            {
                $client = new MockHttpClient([new MockResponse(
                    json_encode(
                        ['data' => ['items' => [['url' => 'https://google.com', 'title' => 'Senior Develoiper']]]],
                        JSON_THROW_ON_ERROR,
                        512
                    )
                )]);

                return $client->request($method, $url, $options);
            }

            public function readWebsite(string $url): Crawler
            {
            }
        });

        $jobs = $jobsProvider->getJobs();

        $this->assertIsArray($jobs);
        $this->assertTrue($jobs['0']['experienced']);
        $this->assertEquals('+3', $jobs['0']['experience_years']);
    }
}
