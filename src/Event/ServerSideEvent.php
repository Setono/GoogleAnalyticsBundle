<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Event;

use Setono\GoogleAnalyticsBundle\ValueObject\Property;
use Setono\GoogleAnalyticsEvents\Event\Event;
use Symfony\Contracts\EventDispatcher\Event as StoppableEvent;

/**
 * Fire this event onto the event dispatcher when you want to track an event asynchronously
 */
final class ServerSideEvent extends StoppableEvent
{
    public Event $event;

    public ?string $clientId;

    /** @var list<Property> */
    public array $properties;

    /**
     * @param string|null $clientId If you do not provide a client id, the client id from the _ga cookie will be used
     * @param list<Property> $properties If you do not provide properties, the properties will be resolved from the configured properties or containers
     */
    public function __construct(Event $event, string $clientId = null, array $properties = [])
    {
        $this->event = $event;
        $this->clientId = $clientId;
        $this->properties = $properties;
    }
}
