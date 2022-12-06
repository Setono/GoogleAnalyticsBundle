<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\ClientId;

use Setono\GoogleAnalyticsBundle\Context\Ga\GaContextInterface;

final class GaBasedClientIdContext implements ClientIdContextInterface
{
    public function __construct(private GaContextInterface $gaContext)
    {
    }

    public function getClientId(): string
    {
        return (string) $this->gaContext->getGa()->clientId;
    }
}
