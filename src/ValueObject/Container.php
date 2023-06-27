<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\ValueObject;

/**
 * This value object represents a Google Tag Manager Container with its corresponding container id
 */
final class Container
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
