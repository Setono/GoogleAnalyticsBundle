<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context;

use Setono\GoogleAnalyticsBundle\Cookie\Ga;
use Symfony\Component\HttpFoundation\RequestStack;

final class GaBasedClientIdContext implements ClientIdContextInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getClientId(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return null;
        }

        $cookieValue = $request->cookies->get('_ga');
        if (!is_string($cookieValue)) {
            return null;
        }

        return Ga::fromString($cookieValue)->clientId;
    }
}
