<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber;

use Setono\GoogleAnalyticsBundle\Context\ClientId\ClientIdContextInterface;
use Setono\GoogleAnalyticsBundle\Message\Command\SendRequest;
use Setono\GoogleAnalyticsBundle\Provider\PropertyProviderInterface;
use Setono\GoogleAnalyticsBundle\Stack\EventStackInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Body;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

final class DispatchRequestsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private EventStackInterface $eventStack,
        private PropertyProviderInterface $propertyProvider,
        private ClientIdContextInterface $clientIdContext,
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
        if ($this->eventStack->isEmpty()) {
            return;
        }

        $properties = $this->propertyProvider->getProperties();

        foreach ($properties as $property) {
            foreach ($this->generateRequests($property->apiSecret, $property->measurementId, $this->clientIdContext->getClientId()) as $request) {
                $this->commandBus->dispatch(new SendRequest($request));
            }
        }
    }

    /**
     * @return \Generator<array-key, Request>
     */
    private function generateRequests(string $apiSecret, string $measurementId, string $clientId): \Generator
    {
        $events = $this->eventStack->all();

        while (count($events) > 0) {
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $request = new Request($apiSecret, $measurementId, Body::create($clientId)->setEvents(array_splice($events, 0, 25)));

            yield $request;
        }
    }
}
