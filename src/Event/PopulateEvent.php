<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;

final class PopulateEvent
{
    public Event $event;

    public array $context;

    public function __construct(Event $event, array $context = [])
    {
        $this->event = $event;
        $this->context = $context;
    }
}
