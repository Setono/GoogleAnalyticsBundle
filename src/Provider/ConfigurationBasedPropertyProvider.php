<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Provider;

use Setono\GoogleAnalyticsBundle\ValueObject\Property;

final class ConfigurationBasedPropertyProvider implements PropertyProviderInterface
{
    /** @var list<array{measurement_id: string, api_secret: null|string}> */
    private array $properties;

    /**
     * @param list<array{measurement_id: string, api_secret: null|string}> $properties
     */
    public function __construct(array $properties)
    {
        // this will filter all properties where the measurement id is empty
        $this->properties = array_values(array_filter($properties, static function (array $property): bool {
            return '' !== $property['measurement_id'];
        }));
    }

    public function getProperties(): array
    {
        return array_map(
            static fn (array $property): Property => new Property($property['measurement_id'], $property['api_secret']),
            $this->properties,
        );
    }
}
