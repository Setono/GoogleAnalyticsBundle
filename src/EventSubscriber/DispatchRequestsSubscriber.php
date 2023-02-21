<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber;

use Setono\GoogleAnalyticsBundle\Factory\RequestFactoryInterface;
use Setono\GoogleAnalyticsBundle\Message\Command\SendRequest;
use Setono\GoogleAnalyticsBundle\Provider\PropertyProviderInterface;
use Setono\GoogleAnalyticsBundle\Stack\EventStackInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * This service will dispatch any server side events onto the command bus
 */
final class DispatchRequestsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly EventStackInterface $eventStack,
        private readonly PropertyProviderInterface $propertyProvider,
        private readonly RequestFactoryInterface $requestFactory,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => 'dispatch',
        ];
    }

    public function dispatch(): void
    {
        $events = $this->eventStack->popServerSide();
        if ([] === $events) {
            return;
        }

        $properties = $this->propertyProvider->getProperties();

        foreach ($properties as $property) {
            $requests = $this->requestFactory->createRequests($property->apiSecret, $property->measurementId, $events);
            foreach ($requests as $request) {
                $this->commandBus->dispatch(new SendRequest($request));
            }
        }
    }
}
