<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Tag;

use Setono\GoogleAnalyticsBundle\Filter\ClientSide\ClientSideFilterInterface;
use Setono\GoogleAnalyticsBundle\Strategy\CollectionStrategyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class LibrarySubscriber implements EventSubscriberInterface
{
    private CollectionStrategyInterface $collectionStrategy;

    private ClientSideFilterInterface $clientSideFilter;

    private bool $injectLibrary;

    public function __construct(
        CollectionStrategyInterface $collectionStrategy,
        ClientSideFilterInterface $clientSideFilter,
        bool $injectLibrary
    ) {
        $this->collectionStrategy = $collectionStrategy;
        $this->clientSideFilter = $clientSideFilter;
        $this->injectLibrary = $injectLibrary;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'add',
        ];
    }

    public function add(RequestEvent $event): void
    {
        if (!$this->injectLibrary || !$event->isMainRequest() || !$this->clientSideFilter->filter(['caller' => self::class])) {
            return;
        }

        $this->collectionStrategy->addLibrary();
    }
}
