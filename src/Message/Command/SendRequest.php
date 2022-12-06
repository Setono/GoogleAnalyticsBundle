<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Message\Command;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;

final class SendRequest implements CommandInterface
{
    public function __construct(public Request $request)
    {
    }
}
