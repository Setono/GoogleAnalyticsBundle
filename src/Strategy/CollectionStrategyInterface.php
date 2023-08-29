<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Strategy;

use Setono\GoogleAnalyticsEvents\Event\Event;

interface CollectionStrategyInterface
{
    public function addLibrary(): void;

    public function addEvent(Event $event): void;
}
