<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Event\ServerSideEvent;
use Setono\GoogleAnalyticsBundle\Message\Command\SendRequest;
use Setono\GoogleAnalyticsBundle\Provider\PropertyProviderInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Body;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * This service will dispatch any server side events onto the command bus
 */
final class HandleServerSideEventSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly PropertyProviderInterface $propertyProvider,
    ) {
        $this->logger = new NullLogger();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ServerSideEvent::class => 'dispatch',
        ];
    }

    public function dispatch(ServerSideEvent $serverSideEvent): void
    {
        if (null === $serverSideEvent->clientId) {
            $this->logger->error('You tried to send an event server side, but no client id was set');

            return;
        }

        $properties = $this->propertyProvider->getProperties();

        foreach ($properties as $property) {
            $request = new Request($property->apiSecret, $property->measurementId, Body::create($serverSideEvent->clientId)->addEvent($serverSideEvent->event));
            $this->commandBus->dispatch(new SendRequest($request));
        }
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
