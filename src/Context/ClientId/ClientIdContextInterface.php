<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\ClientId;

interface ClientIdContextInterface
{
    public function getClientId(): string;
}
