<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Message\Command;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;

final class SendRequest implements CommandInterface
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
