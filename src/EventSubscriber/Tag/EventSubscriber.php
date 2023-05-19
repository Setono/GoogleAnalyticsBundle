<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Tag;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;
use Setono\TagBag\Tag\InlineScriptTag;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\TagBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EventSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    private LoggerInterface $logger;

    private TagBagInterface $tagBag;

    public function __construct(TagBagInterface $tagBag)
    {
        $this->logger = new NullLogger();
        $this->tagBag = $tagBag;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClientSideEvent::class => ['add', -1000],
        ];
    }

    public function add(ClientSideEvent $clientSideEvent): void
    {
        $this->tagBag->add(InlineScriptTag::create(
            $this->generateGtag($clientSideEvent->event),
        )->withSection(
            TagInterface::SECTION_HEAD,
        ));
    }

    private function generateGtag(Event $event): string
    {
        try {
            $json = json_encode($event->getPayload(Request::TRACKING_CONTEXT_CLIENT_SIDE), \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error(sprintf('Could not json encode the event %s. The JSON error was: %s', $event->getEventName(), $e->getMessage()));

            return sprintf("console.error('Could not json encode the event %s');", $event->getEventName());
        }

        return sprintf("gtag('event', '%s', %s);", $event->getEventName(), $json);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
