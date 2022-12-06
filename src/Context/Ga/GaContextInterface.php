<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\Ga;

use Setono\GoogleAnalyticsBundle\Cookie\Ga;

interface GaContextInterface
{
    public function getGa(): Ga;
}
