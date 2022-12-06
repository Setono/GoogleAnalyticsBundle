<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\SessionId;

interface SessionIdContextInterface
{
    /**
     * NOTICE this has nothing to do with the PHP session id. It's the session id used in Google Analytics events
     */
    public function getSessionId(): SessionId;
}
