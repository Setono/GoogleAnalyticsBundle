<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Symfony\Contracts\EventDispatcher\Event as StoppableEvent;

final class FilterEvent extends StoppableEvent
{
    public Event $event;

    public array $context;

    public function __construct(Event $event, array $context = [])
    {
        $this->event = $event;
        $this->context = $context;
    }
}
