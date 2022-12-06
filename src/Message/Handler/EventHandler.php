<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Message\Handler;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\FilterEvent;
use Setono\GoogleAnalyticsBundle\Event\PopulateEvent;
use Setono\GoogleAnalyticsBundle\Stack\EventStackInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class EventHandler implements MessageHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;

    private EventStackInterface $eventStack;

    public function __construct(EventDispatcherInterface $eventDispatcher, EventStackInterface $eventStack)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->eventStack = $eventStack;
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
