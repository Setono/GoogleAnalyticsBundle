<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Symfony\Contracts\EventDispatcher\Event as StoppableEvent;

/**
 * Fire this event onto the event dispatcher when you want to track events while the user is browsing
 */
final class ClientSideEvent extends StoppableEvent
{
    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }
}
