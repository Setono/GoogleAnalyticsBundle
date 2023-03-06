<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Symfony\Contracts\EventDispatcher\Event as StoppableEvent;

/**
 * Fire this event onto the event dispatcher when you want to track an event asynchronously
 */
final class ServerSideEvent extends StoppableEvent
{
    public function __construct(
        public readonly Event $event,
        /**
         * If you do not provide a client id, the client id from the _ga cookie will be used
         */
        public ?string $clientId = null,
    ) {
    }
}
