<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Stack;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;

final class EventStack implements EventStackInterface, \IteratorAggregate
{
    /** @var list<Event> */
    private array $events = [];

    public function all(): array
    {
        return $this->events;
    }

    public function push(Event $event): void
    {
        $this->events[] = $event;
    }

    public function isEmpty(): bool
    {
        return [] === $this->events;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->events);
    }

    public function count(): int
    {
        return count($this->events);
    }
}
