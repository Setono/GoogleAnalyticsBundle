<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Filter\ClientSide;

interface ClientSideFilterInterface
{
    /**
     * If the method returns false, the client side tags/scripts are not rendered on your page.
     * You can use this to avoid rendering Google Analytics tags on your admin pages for example
     *
     * @param array<string, mixed> $context
     */
    public function filter(array $context = []): bool;
}
