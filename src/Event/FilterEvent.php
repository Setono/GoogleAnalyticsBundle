<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Symfony\Contracts\EventDispatcher\Event as StoppableEvent;

/**
 * Listen to this event if you want to filter certain events. Use the FilterEvent::stopPropagation() to filter the event
 */
final class FilterEvent extends StoppableEvent
{
    public function __construct(public Event $event, public array $context = [])
    {
    }
}
