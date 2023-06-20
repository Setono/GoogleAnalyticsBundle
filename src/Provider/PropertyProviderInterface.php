<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Provider;

use Setono\GoogleAnalyticsBundle\ValueObject\Property;

interface PropertyProviderInterface
{
    /**
     * Returns the applicable properties(s) for the current request
     *
     * @return list<Property>
     */
    public function getProperties(): array;
}
