<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\ValueObject;

use Webmozart\Assert\Assert;

/**
 * This value object represents a Google Analytics 4 property with its corresponding measurement id and optional api secret
 */
final class Property
{
    public string $measurementId;

    public ?string $apiSecret;

    public function __construct(string $measurementId, string $apiSecret = null)
    {
        Assert::startsWith(
            $measurementId,
            'G-',
            sprintf('The measurement id does not start with "G-". The given input was: "%s"', $measurementId),
        );

        $this->measurementId = $measurementId;
        $this->apiSecret = $apiSecret;
    }
}
