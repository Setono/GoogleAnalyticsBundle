<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Property;

use Webmozart\Assert\Assert;

final class Property
{
    public string $apiSecret;

    public string $measurementId;

    public function __construct(string $apiSecret, string $measurementId)
    {
        Assert::true(
            str_starts_with($measurementId, 'G-'),
            sprintf('The measurement id does not start with "G-". The given input was: "%s"', $measurementId),
        );

        $this->apiSecret = $apiSecret;
        $this->measurementId = $measurementId;
    }
}
