<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;

/**
 * Listen to this event when you want to add/edit/remove properties on the Event
 */
final class PopulateEvent
{
    public function __construct(public Event $event, public array $context = [])
    {
    }
}
