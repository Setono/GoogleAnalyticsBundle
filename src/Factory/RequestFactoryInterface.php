<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Factory;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;

interface RequestFactoryInterface
{
    /**
     * @param list<Event> $events
     *
     * @return iterable<Request>
     */
    public function createRequests(string $apiSecret, string $measurementId, array $events): iterable;
}
