<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Filter;

use Setono\GoogleAnalyticsBundle\Event\FilterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class FilterNoClientIdSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FilterEvent::class => 'filter',
        ];
    }

    public function filter(FilterEvent $event): void
    {
        if ($event->event->getClientId() === null) {
            $event->stopPropagation();
        }
    }
}
