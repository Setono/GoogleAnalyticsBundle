<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Strategy;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Provider\ContainerProviderInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Event;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Request;
use Setono\TagBag\Tag\InlineScriptTag;
use Setono\TagBag\Tag\ScriptTag;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\TagBagInterface;

final class TagManagerCollectionStrategy implements CollectionStrategyInterface, LoggerAwareInterface
{
    private LoggerInterface $logger;

    private TagBagInterface $tagBag;

    private ContainerProviderInterface $containerProvider;

    public function __construct(TagBagInterface $tagBag, ContainerProviderInterface $containerProvider)
    {
        $this->logger = new NullLogger();
        $this->tagBag = $tagBag;
        $this->containerProvider = $containerProvider;
    }

    public function addLibrary(): void
    {
        $this->tagBag->add(
            InlineScriptTag::create("var dataLayer=window.dataLayer||[];dataLayer.push({'gtm.start':new Date().getTime(),event:'gtm.js'})")
                ->withSection(TagInterface::SECTION_HEAD)
                ->withPriority(100)
        );

        $containers = $this->containerProvider->getContainers();

        if (count($containers) > 0) {
            foreach ($this->containerProvider->getContainers() as $container) {
                $this->tagBag->add(ScriptTag::create(sprintf(
                    'https://www.googletagmanager.com/gtm.js?id=%s',
                    $container->id
                ))->async());
            }
        } else {
            $this->tagBag->add(InlineScriptTag::create('console.error("[Setono Google Analytics Bundle] You have not configured any Google Tag Manager containers.")'));
        }
    }

    public function addEvent(Event $event): void
    {
        try {
            $json = json_encode($event->getPayload(Request::TRACKING_CONTEXT_CLIENT_SIDE_TAG_MANAGER), \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error(sprintf('Could not json encode the event %s. The JSON error was: %s', $event->getEventName(), $e->getMessage()));

            return;
        }

        $this->tagBag->add(InlineScriptTag::create(sprintf('dataLayer.push({ ecommerce: null }); dataLayer.push(%s);', $json)));
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
