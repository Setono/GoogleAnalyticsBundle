<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Provider;

use Setono\GoogleAnalyticsBundle\Property\Property;

final class ConfigurationBasedPropertyProvider implements PropertyProviderInterface
{
    /**
     * @param list<array{api_secret: string, measurement_id: string}> $properties
     */
    public function __construct(private readonly array $properties)
    {
    }

    public function getProperties(): array
    {
        return array_map(static fn (array $property): Property => new Property($property['api_secret'], $property['measurement_id']), $this->properties);
    }
}
