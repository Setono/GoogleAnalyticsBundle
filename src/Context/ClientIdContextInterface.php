<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context;

interface ClientIdContextInterface
{
    public function getClientId(): ?string;
}
