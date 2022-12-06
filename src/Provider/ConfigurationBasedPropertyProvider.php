<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Provider;

use Setono\GoogleAnalyticsBundle\Property\Property;

final class ConfigurationBasedPropertyProvider implements PropertyProviderInterface
{
    /** @var list<array{api_secret: string, measurement_id: string}> */
    private array $properties;

    /**
     * @param list<array{api_secret: string, measurement_id: string}> $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function getProperties(): array
    {
        return array_map(static function (array $property): Property {
            return new Property($property['api_secret'], $property['measurement_id']);
        }, $this->properties);
    }
}
