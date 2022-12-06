<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\Ga;

interface GaContextInterface
{
    public function getGa(): Ga;
}
