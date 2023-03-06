<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Property;

use Webmozart\Assert\Assert;

final class Property
{
    public function __construct(
        public readonly string $apiSecret,
        public readonly string $measurementId,
    ) {
        Assert::true(str_starts_with($measurementId, 'G-'));
    }
}
