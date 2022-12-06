<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Populate;

use Setono\GoogleAnalyticsBundle\Context\SessionId\SessionIdContextInterface;
use Setono\GoogleAnalyticsBundle\Event\PopulateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PopulateSessionIdSubscriber implements EventSubscriberInterface
{
    public function __construct(private SessionIdContextInterface $sessionIdContext)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PopulateEvent::class => 'populate',
        ];
    }

    public function populate(PopulateEvent $event): void
    {
        $event->event->setSessionId($this->sessionIdContext->getSessionId()->value);
    }
}
