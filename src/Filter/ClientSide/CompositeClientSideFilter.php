<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Filter\ClientSide;

use Setono\CompositeCompilerPass\CompositeService;

/**
 * @extends CompositeService<ClientSideFilterInterface>
 */
final class CompositeClientSideFilter extends CompositeService implements ClientSideFilterInterface
{
    public function filter(array $context = []): bool
    {
        foreach ($this->services as $service) {
            if (!$service->filter($context)) {
                return false;
            }
        }

        return true;
    }
}
