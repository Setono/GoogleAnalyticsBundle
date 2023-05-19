<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\ConsentChecker;

use Setono\Consent\ConsentCheckerInterface as BaseConsentCheckerInterface;

/**
 * When consent is enabled, this is the consent checker used. It uses the consent checker from the consent bundle
 */
final class ConsentContractsBasedConsentChecker implements ConsentCheckerInterface
{
    private BaseConsentCheckerInterface $consentChecker;

    public function __construct(?BaseConsentCheckerInterface $consentChecker)
    {
        if (null === $consentChecker) {
            throw new \RuntimeException('You need to install the setono/consent-bundle:^1.0 to use the consent functionality');
        }

        $this->consentChecker = $consentChecker;
    }

    public function isGranted(string $consent): bool
    {
        return $this->consentChecker->isGranted($consent);
    }
}
