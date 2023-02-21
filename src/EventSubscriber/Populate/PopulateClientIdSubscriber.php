<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Populate;

use Setono\GoogleAnalyticsBundle\Context\Ga\GaContextInterface;
use Setono\GoogleAnalyticsBundle\Event\PopulateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Set the client id (if it's not set) on the event based on the _ga cookie value
 */
final class PopulateClientIdSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly GaContextInterface $gaContext)
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
        if ($event->event->getClientId() !== null) {
            return;
        }

        $event->event->setClientId((string) $this->gaContext->getGa()?->clientId);
    }
}
