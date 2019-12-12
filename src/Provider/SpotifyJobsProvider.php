<?php

declare(strict_types=1);

namespace App\Provider;

use App\Detector\ExperienceDetector;
use App\Reader\UrlReaderInterface;
use function json_decode;

class SpotifyJobsProvider implements JobsProviderInterface
{
    private const SPOTIFY_JOBS_URL = 'https://www.spotifyjobs.com/wp-admin/admin-ajax.php?action=get_jobs&pageNr=1&perPage=16&featuredJobs=&category=0&location=0&search=&locations[]=sweden';

    private UrlReaderInterface $urlReader;

    public function __construct(UrlReaderInterface $urlReader)
    {
        $this->urlReader = $urlReader;
    }

    public function getJobs(): array
    {
        $response = $this->urlReader->readAPI('POST', self::SPOTIFY_JOBS_URL, [
            'body' => 'action=get_jobs&pageNr=1&perPage=16&featuredJobs=&category=0&location=0&search=&locations%5B%5D=sweden',
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            ],
        ]);

        $data = json_decode($response->getContent(), true, 10, JSON_THROW_ON_ERROR);
        $jobs = [];
        foreach ($data['data']['items'] as $item) {
            $job = [
                'url' => $item['url'],
                'title' => html_entity_decode($item['title'], ENT_NOQUOTES, 'UTF-8'),
            ];
            $job['experienced'] = ExperienceDetector::experienceRequired($job);
            $experienceYears = ExperienceDetector::getYearsOfExperience($job);
            $job['experience_years'] = $experienceYears ? '+'.$experienceYears : null;
            $jobs[] = $job;
        }

        return $jobs;
    }

    public function getJobsDescription(string $jobUrl): string
    {
        return $this->urlReader->readWebsite($jobUrl)->filter('.entry-content .column-inner')->text();
    }
}
