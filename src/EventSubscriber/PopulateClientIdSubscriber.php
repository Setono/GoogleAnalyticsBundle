<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber;

use Setono\GoogleAnalyticsBundle\Context\ClientIdContextInterface;
use Setono\GoogleAnalyticsBundle\Event\ServerSideEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PopulateClientIdSubscriber implements EventSubscriberInterface
{
    private ClientIdContextInterface $clientIdContext;

    public function __construct(ClientIdContextInterface $clientIdContext)
    {
        $this->clientIdContext = $clientIdContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ServerSideEvent::class => 'populate',
        ];
    }

    public function populate(ServerSideEvent $event): void
    {
        // only populate the client id if it hasn't been set
        if (null !== $event->clientId) {
            return;
        }

        $event->clientId = $this->clientIdContext->getClientId();
    }
}
