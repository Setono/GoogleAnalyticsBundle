<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Tag;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\Consent\ConsentCheckerInterface;
use Setono\Consent\Consents;
use Setono\GoogleAnalyticsBundle\Stack\EventStackInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Setono\TagBag\Tag\ConsentableInlineScriptTag;
use Setono\TagBag\Tag\InlineScriptTag;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\TagBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class EventSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly TagBagInterface $tagBag,
        private readonly EventStackInterface $eventStack,
        private readonly ?ConsentCheckerInterface $consentChecker,
        private readonly bool $consentEnabled,
    ) {
        $this->logger = new NullLogger();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['add', -800],
        ];
    }

    public function add(ResponseEvent $responseEvent): void
    {
        if (!$responseEvent->isMainRequest() || $this->eventStack->isEmpty()) {
            return;
        }

        foreach ($this->eventStack->popClientSide() as $event) {
            if ($this->consentEnabled && null !== $this->consentChecker && !$this->consentChecker->isGranted(Consents::CONSENT_STATISTICS)) {
                $this->tagBag->add(ConsentableInlineScriptTag::create($this->generateGtag($event), Consents::CONSENT_STATISTICS));
            } else {
                $this->tagBag->add(InlineScriptTag::create($this->generateGtag($event))->withSection(TagInterface::SECTION_HEAD));
            }
        }
    }

    private function generateGtag(Event $event): string
    {
        try {
            $json = json_encode($event->jsonSerialize()['params'], \JSON_THROW_ON_ERROR | \JSON_FORCE_OBJECT);
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
