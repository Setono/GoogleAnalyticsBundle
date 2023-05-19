<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Message\Handler;

use Setono\GoogleAnalyticsBundle\Message\Command\SendRequest;
use Setono\GoogleAnalyticsMeasurementProtocol\Client\ClientInterface;

final class SendRequestHandler
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke(SendRequest $message): void
    {
        $this->client->sendRequest($message->request);
    }
}
