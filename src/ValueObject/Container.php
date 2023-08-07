<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\ValueObject;

/**
 * This value object represents a Google Tag Manager Container with its corresponding container id
 */
final class Container
{
    public string $id;

    /**
     * The property is only used if you want to send server side events
     */
    public ?Property $property;

    public function __construct(string $id, Property $property = null)
    {
        $this->id = $id;
        $this->property = $property;
    }

    /**
     * @param array{container_id: string, property: array{measurement_id?: string, api_secret?: string}} $data
     */
    public static function fromArray(array $data): self
    {
        $property = null;
        if (isset($data['property']['measurement_id'], $data['property']['api_secret'])) {
            $property = new Property($data['property']['measurement_id'], $data['property']['api_secret']);
        }

        return new self($data['container_id'], $property);
    }
}
