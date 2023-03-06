<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\ConsentChecker;

/**
 * This is the consent checker used when consent is disabled in the bundle configuration
 */
final class GrantAllConsentChecker implements ConsentCheckerInterface
{
    public function isGranted(string $consent): bool
    {
        return true;
    }
}
