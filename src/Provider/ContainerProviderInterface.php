<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Provider;

use Setono\GoogleAnalyticsBundle\ValueObject\Container;

interface ContainerProviderInterface
{
    /**
     * Returns the applicable container(s) for the current request
     *
     * @return list<Container>
     */
    public function getContainers(): array;
}
