<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Tag;

use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsBundle\Strategy\CollectionStrategyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EventSubscriber implements EventSubscriberInterface
{
    private CollectionStrategyInterface $collectionStrategy;

    public function __construct(CollectionStrategyInterface $collectionStrategy)
    {
        $this->collectionStrategy = $collectionStrategy;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClientSideEvent::class => ['add', -1000],
        ];
    }

    public function add(ClientSideEvent $clientSideEvent): void
    {
        $this->collectionStrategy->addEvent($clientSideEvent->event);
    }
}
