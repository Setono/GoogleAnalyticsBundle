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
        // this will filter all properties where the api secret _or_ the measurement id is empty
        $this->properties = array_values(array_filter($properties, static function (array $property): bool {
            return '' !== $property['api_secret'] && '' !== $property['measurement_id'];
        }));
    }

    public function getProperties(): array
    {
        return array_map(
            static fn (array $property): Property => new Property($property['api_secret'], $property['measurement_id']),
            $this->properties,
        );
    }
}
