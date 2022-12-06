<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\Ga;

final class NewGaContext implements GaContextInterface
{
    public function getGa(): Ga
    {
        return Ga::new();
    }
}
