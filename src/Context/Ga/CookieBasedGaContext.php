<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\Ga;

use Setono\GoogleAnalyticsBundle\Cookie\Ga;
use Symfony\Component\HttpFoundation\RequestStack;

final class CookieBasedGaContext implements GaContextInterface
{
    public function __construct(private GaContextInterface $decorated, private RequestStack $requestStack)
    {
    }

    public function getGa(): Ga
    {
        $cookieValue = $this->requestStack->getMainRequest()?->cookies->get('_ga');
        if (!is_string($cookieValue)) {
            return $this->decorated->getGa();
        }

        return Ga::fromString($cookieValue);
    }
}
