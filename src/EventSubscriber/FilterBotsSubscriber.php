<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber;

use Setono\BotDetectionBundle\BotDetector\BotDetectorInterface;
use Setono\GoogleAnalyticsBundle\Event\FilterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class FilterBotsSubscriber implements EventSubscriberInterface
{
    private BotDetectorInterface $botDetector;

    public function __construct(BotDetectorInterface $botDetector)
    {
        $this->botDetector = $botDetector;
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
