<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\Ga;

use Setono\GoogleAnalyticsBundle\Cookie\Ga;

final class CachedGaContext implements GaContextInterface
{
    private ?Ga $ga = null;

    public function __construct(private GaContextInterface $decorated)
    {
    }

    public function getGa(): Ga
    {
        if (null === $this->ga) {
            $this->ga = $this->decorated->getGa();
        }

        return $this->ga;
    }
}
