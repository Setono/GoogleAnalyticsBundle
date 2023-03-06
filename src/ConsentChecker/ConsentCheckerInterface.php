<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\ConsentChecker;

interface ConsentCheckerInterface
{
    public function isGranted(string $consent): bool;
}
