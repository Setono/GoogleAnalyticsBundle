<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Filter;

use Setono\BotDetectionBundle\BotDetector\BotDetectorInterface;
use Setono\GoogleAnalyticsBundle\Event\FilterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class FilterBotsSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly BotDetectorInterface $botDetector)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FilterEvent::class => 'filter',
        ];
    }

    public function filter(FilterEvent $event): void
    {
        if ($this->botDetector->isBotRequest()) {
            $event->stopPropagation();
        }
    }
}
