<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Stack;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;

interface EventStackInterface
{
    /**
     * Returns all client events and removes them from the stack
     *
     * @return list<Event>
     */
    public function popClientSide(): array;

    /**
     * Returns all server side events and removes them from the stack
     *
     * @return list<Event>
     */
    public function popServerSide(): array;

    public function push(Event $event): void;

    public function isEmpty(): bool;
}
