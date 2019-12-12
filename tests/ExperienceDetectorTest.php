<?php

namespace App\Tests;

use App\Detector\ExperienceDetector;
use PHPUnit\Framework\TestCase;

class ExperienceDetectorTest extends TestCase
{
    public function testExperienceIsDetected()
    {
        $this->assertTrue(ExperienceDetector::experienceRequired(['title' => 'Senior developer']));
        $this->assertFalse(ExperienceDetector::experienceRequired(['title' => 'Junior developer']));
        $this->assertTrue(ExperienceDetector::experienceRequired(['title' => 'Head of marketing']));
        $this->assertTrue(ExperienceDetector::experienceRequired(['title' => 'Team Lead']));
        $this->assertFalse(ExperienceDetector::experienceRequired([]));
    }
}
