<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\Ga;

use Symfony\Component\HttpFoundation\RequestStack;

final class CookieBasedGaContext implements GaContextInterface
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getGa(): ?Ga
    {
        $cookieValue = $this->requestStack->getMainRequest()?->cookies->get('_ga');
        if (!is_string($cookieValue)) {
            return null;
        }

        try {
            return Ga::fromString($cookieValue);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }
}
