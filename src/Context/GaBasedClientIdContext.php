<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context;

use Setono\GoogleAnalyticsBundle\Cookie\Ga;
use Symfony\Component\HttpFoundation\RequestStack;

final class GaBasedClientIdContext implements ClientIdContextInterface
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getClientId(): ?string
    {
        $cookieValue = $this->requestStack->getMainRequest()?->cookies->get('_ga');
        if (!is_string($cookieValue)) {
            return null;
        }

        return Ga::fromString($cookieValue)->clientId;
    }
}
