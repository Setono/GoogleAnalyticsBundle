<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Stack;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;

interface EventStackInterface extends \Countable, \Traversable
{
    /**
     * @return list<Event>
     */
    public function all(): array;

    public function push(Event $event): void;

    public function isEmpty(): bool;
}
