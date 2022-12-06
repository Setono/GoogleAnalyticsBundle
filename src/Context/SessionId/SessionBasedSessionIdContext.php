<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\SessionId;

// todo should it be cookie based instead?
use Symfony\Component\HttpFoundation\RequestStack;

final class SessionBasedSessionIdContext implements SessionIdContextInterface
{
    public const SESSION_KEY = 'setono_google_analytics_session_id';

    public function __construct(private SessionIdContextInterface $decorated, private RequestStack $requestStack)
    {
    }

    public function getSessionId(): SessionId
    {
        /** @var mixed $sessionId */
        $sessionId = $this->requestStack->getMainRequest()?->getSession()->get(self::SESSION_KEY);

        // todo this expiry time should be configurable
        if ($sessionId instanceof SessionId && $sessionId->timestamp >= (time() - 1800)) {
            return $sessionId;
        }

        return $this->decorated->getSessionId();
    }
}
