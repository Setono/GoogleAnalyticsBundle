<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Strategy;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\Consent\Consents;
use Setono\GoogleAnalyticsBundle\ConsentChecker\ConsentCheckerInterface;
use Setono\GoogleAnalyticsBundle\Provider\PropertyProviderInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;
use Setono\TagBag\Tag\ConsentableScriptTag;
use Setono\TagBag\Tag\InlineScriptTag;
use Setono\TagBag\Tag\ScriptTag;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\TagBagInterface;

final class GtagCollectionStrategy implements CollectionStrategyInterface, LoggerAwareInterface
{
    private LoggerInterface $logger;

    private TagBagInterface $tagBag;

    private PropertyProviderInterface $propertyProvider;

    private ConsentCheckerInterface $consentChecker;

    public function __construct(
        TagBagInterface $tagBag,
        PropertyProviderInterface $propertyProvider,
        ConsentCheckerInterface $consentChecker
    ) {
        $this->logger = new NullLogger();
        $this->tagBag = $tagBag;
        $this->propertyProvider = $propertyProvider;
        $this->consentChecker = $consentChecker;
    }

    public function addLibrary(): void
    {
        // By adding this javascript to all pages no matter if we have any properties we make it easier for ourselves
        // when adding tags in other subscribers, since they will be using the gtag method, and therefore we don't
        // have to check if there are any properties when adding all other tags
        $this->tagBag->add(InlineScriptTag::create('function gtag(){dataLayer.push(arguments)}window.dataLayer=window.dataLayer||[],gtag("js",new Date);')
            ->withSection(TagInterface::SECTION_HEAD)->withPriority(90));

        $properties = $this->propertyProvider->getProperties();
        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $this->tagBag->add(InlineScriptTag::create(sprintf(
                    'gtag("config", "%s");',
                    $property->measurementId,
                ))->withSection(TagInterface::SECTION_HEAD)
                    ->withPriority(80))
                ;
            }

            $src = sprintf('https://www.googletagmanager.com/gtag/js?id=%s', $properties[0]->measurementId);

            if ($this->consentChecker->isGranted(self::getConsentType())) {
                $this->tagBag->add(ScriptTag::create($src)->defer()->withSection(TagInterface::SECTION_HEAD)->withPriority(100));
            } else {
                $this->tagBag->add(ConsentableScriptTag::create($src, self::getConsentType()));
            }
        } else {
            $this->tagBag->add(InlineScriptTag::create('console.error("[Setono Google Analytics Bundle] You have not configured any Google Analytics properties.")'));
        }
    }

    public function addEvent(Event $event): void
    {
        $this->tagBag->add(
            InlineScriptTag::create($this->generateGtag($event))->withSection(TagInterface::SECTION_HEAD)
        );
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
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

    private static function getConsentType(): string
    {
        return class_exists(Consents::class) ? Consents::CONSENT_STATISTICS : 'statistics';
    }
}
