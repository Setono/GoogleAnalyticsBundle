<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Factory;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Body;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;

final class RequestFactory implements RequestFactoryInterface
{
    public function createRequests(string $apiSecret, string $measurementId, array $events): \Generator
    {
        /** @var array<string, list<Event>> $eventsIndexed */
        $eventsIndexed = [];

        foreach ($events as $event) {
            $clientId = $event->getClientId();
            if (null === $clientId) {
                continue; // todo handle this somehow?
            }

            $eventsIndexed[$clientId][] = $event;
        }

        foreach ($eventsIndexed as $clientId => $eventsByClientId) {
            while (count($eventsByClientId) > 0) {
                /**
                 * Note that the cast to string IS required because a client id can be a number and if you provide a number
                 * as the index to an array in PHP, PHP will convert that number to an int. See https://github.com/vimeo/psalm/issues/9295
                 *
                 * @psalm-suppress ArgumentTypeCoercion,RedundantCastGivenDocblockType
                 */
                yield new Request($apiSecret, $measurementId, Body::create((string) $clientId)->setEvents(array_splice($eventsByClientId, 0, 25)));
            }
        }
    }
}
