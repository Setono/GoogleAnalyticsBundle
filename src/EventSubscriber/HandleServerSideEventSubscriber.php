<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Event\ServerSideEvent;
use Setono\GoogleAnalyticsBundle\Message\Command\SendRequest;
use Setono\GoogleAnalyticsBundle\Provider\ContainerProviderInterface;
use Setono\GoogleAnalyticsBundle\Provider\PropertyProviderInterface;
use Setono\GoogleAnalyticsBundle\ValueObject\Property;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * This service will dispatch any server side events onto the command bus
 */
final class HandleServerSideEventSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    private LoggerInterface $logger;

    private MessageBusInterface $commandBus;

    private PropertyProviderInterface $propertyProvider;

    private ContainerProviderInterface $containerProvider;

    private bool $gtagEnabled;

    public function __construct(MessageBusInterface $commandBus, PropertyProviderInterface $propertyProvider, ContainerProviderInterface $containerProvider, bool $gtagEnabled)
    {
        $this->logger = new NullLogger();
        $this->commandBus = $commandBus;
        $this->propertyProvider = $propertyProvider;
        $this->containerProvider = $containerProvider;
        $this->gtagEnabled = $gtagEnabled;
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

        $properties = [] === $serverSideEvent->properties ? $this->getProperties() : $serverSideEvent->properties;

        foreach ($properties as $property) {
            if (null === $property->apiSecret) {
                $this->logger->error(sprintf(
                    'You tried to send an event server side, but the API secret is not set on the property with measurement id %s',
                    $property->measurementId
                ));

                continue;
            }

            $request = (new Request($property->apiSecret, $property->measurementId, $serverSideEvent->clientId))->addEvent($serverSideEvent->event);

            $this->commandBus->dispatch(new SendRequest($request));
        }
    }

    /**
     * @return list<Property>
     */
    private function getProperties(): array
    {
        $properties = [];

        if ($this->gtagEnabled) {
            return $this->propertyProvider->getProperties();
        }

        foreach ($this->containerProvider->getContainers() as $container) {
            if (null !== $container->property) {
                $properties[] = $container->property;
            }
        }

        return $properties;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
