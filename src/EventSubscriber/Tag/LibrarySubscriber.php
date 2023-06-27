<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Tag;

use Setono\GoogleAnalyticsBundle\Strategy\CollectionStrategyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class LibrarySubscriber implements EventSubscriberInterface
{
    private CollectionStrategyInterface $collectionStrategy;

    private bool $injectLibrary;

    public function __construct(CollectionStrategyInterface $collectionStrategy, bool $injectLibrary)
    {
        $this->collectionStrategy = $collectionStrategy;
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
        if (!$this->injectLibrary || !$event->isMainRequest()) {
            return;
        }

        $this->collectionStrategy->addLibrary();
    }
}
