<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\SessionId;

final class CachedSessionIdContext implements SessionIdContextInterface
{
    private ?SessionId $sessionId = null;

    public function __construct(private SessionIdContextInterface $decorated)
    {
    }

    public function getSessionId(): SessionId
    {
        if (null === $this->sessionId) {
            $this->sessionId = $this->decorated->getSessionId();
        }

        return $this->sessionId;
    }
}
