<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Property;

use Webmozart\Assert\Assert;

final class Property
{
    /** @readonly */
    public string $apiSecret;

    /** @readonly */
    public string $measurementId;

    public function __construct(string $apiSecret, string $measurementId)
    {
        Assert::true(str_starts_with($measurementId, 'G-'));

        $this->apiSecret = $apiSecret;
        $this->measurementId = $measurementId;
    }
}
