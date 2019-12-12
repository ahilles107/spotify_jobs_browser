<?php

declare(strict_types=1);

namespace App\Detector;

class ExperienceDetector
{
    public static function experienceRequired(array $job): bool
    {
        if (!array_key_exists('title', $job)) {
            return false;
        }

        return 1 === preg_match('/Senior|Head|Lead/i', $job['title']);
    }

    public static function getYearsOfExperience(array $job): ?int
    {
        if (!array_key_exists('title', $job)) {
            return null;
        }

        if (false !== strpos($job['title'], 'Senior')) {
            return 3;
        } elseif (false !== strpos($job['title'], 'Head')) {
            return 5;
        } elseif (false !== strpos($job['title'], 'Lead')) {
            return 5;
        } elseif (false !== strpos($job['title'], 'Junior')) {
            return 1;
        }

        return null;
    }
}
