<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\SessionId;

final class NewSessionIdContext implements SessionIdContextInterface
{
    public function getSessionId(): SessionId
    {
        return SessionId::new();
    }
}
