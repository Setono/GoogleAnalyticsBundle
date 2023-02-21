<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Property;

use Webmozart\Assert\Assert;

final class Property
{
    /** @readonly */
    public readonly string $measurementId;

    public function __construct(/** @readonly */
    public readonly string $apiSecret,
        string $measurementId,
    ) {
        Assert::true(str_starts_with($measurementId, 'G-'));
        $this->measurementId = $measurementId;
    }
}
