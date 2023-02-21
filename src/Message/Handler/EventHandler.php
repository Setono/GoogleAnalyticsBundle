<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Message\Handler;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\FilterEvent;
use Setono\GoogleAnalyticsBundle\Event\PopulateEvent;
use Setono\GoogleAnalyticsBundle\Stack\EventStackInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;

final class EventHandler
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EventStackInterface $eventStack,
    ) {
    }

    public function __invoke(Event $event): void
    {
        $this->eventDispatcher->dispatch(new PopulateEvent($event));

        $filterEvent = new FilterEvent($event);
        $this->eventDispatcher->dispatch($filterEvent);

        if ($filterEvent->isPropagationStopped()) {
            return;
        }

        $this->eventStack->push($event);
    }
}
