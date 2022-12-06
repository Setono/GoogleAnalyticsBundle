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
        // todo is it really only the client id part that is the client id? Is it not the client id + timestamp together?
        return (string) $this->gaContext->getGa()->clientId;
    }
}
