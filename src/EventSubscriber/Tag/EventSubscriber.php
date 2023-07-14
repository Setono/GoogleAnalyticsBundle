<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Tag;

use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsBundle\Filter\ClientSide\ClientSideFilterInterface;
use Setono\GoogleAnalyticsBundle\Strategy\CollectionStrategyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EventSubscriber implements EventSubscriberInterface
{
    private CollectionStrategyInterface $collectionStrategy;

    private ClientSideFilterInterface $clientSideFilter;

    public function __construct(
        CollectionStrategyInterface $collectionStrategy,
        ClientSideFilterInterface $clientSideFilter
    ) {
        $this->collectionStrategy = $collectionStrategy;
        $this->clientSideFilter = $clientSideFilter;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClientSideEvent::class => ['add', -1000],
        ];
    }

    public function add(ClientSideEvent $clientSideEvent): void
    {
        if (!$this->clientSideFilter->filter(['caller' => self::class, 'event' => $clientSideEvent])) {
            return;
        }

        $this->collectionStrategy->addEvent($clientSideEvent->event);
    }
}
