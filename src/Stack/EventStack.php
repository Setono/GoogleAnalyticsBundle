<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Stack;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;

final class EventStack implements EventStackInterface
{
    /** @var list<Event> */
    private array $events = [];

    public function popClientSide(): array
    {
        $res = array_values(array_filter($this->events, static function (Event $event): bool {
            return !$event->isServerSide();
        }));

        $this->events = array_values(array_filter($this->events, static function (Event $event): bool {
            return $event->isServerSide();
        }));

        return $res;
    }

    public function popServerSide(): array
    {
        $res = array_values(array_filter($this->events, static function (Event $event): bool {
            return $event->isServerSide();
        }));

        $this->events = array_values(array_filter($this->events, static function (Event $event): bool {
            return !$event->isServerSide();
        }));

        return $res;
    }

    public function push(Event $event): void
    {
        $this->events[] = $event;
    }

    public function isEmpty(): bool
    {
        return [] === $this->events;
    }
}
