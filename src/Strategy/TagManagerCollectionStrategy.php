<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Strategy;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Provider\ContainerProviderInterface;
use Setono\GoogleAnalyticsEvents\Event\Event;
use Setono\GoogleAnalyticsEvents\Exception\WriterException;
use Setono\GoogleAnalyticsEvents\Writer\Writer;
use Setono\TagBag\Tag\InlineScriptTag;
use Setono\TagBag\Tag\ScriptTag;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\TagBagInterface;

final class TagManagerCollectionStrategy implements CollectionStrategyInterface, LoggerAwareInterface
{
    private LoggerInterface $logger;

    private TagBagInterface $tagBag;

    private ContainerProviderInterface $containerProvider;

    private Writer $writer;

    private string $dataLayerVariable;

    public function __construct(
        TagBagInterface $tagBag,
        ContainerProviderInterface $containerProvider,
        Writer $writer,
        string $dataLayerVariable = 'dataLayer'
    ) {
        $this->logger = new NullLogger();
        $this->tagBag = $tagBag;
        $this->containerProvider = $containerProvider;
        $this->writer = $writer;
        $this->dataLayerVariable = $dataLayerVariable;
    }

    public function addLibrary(): void
    {
        $this->tagBag->add(
            InlineScriptTag::create(sprintf(
                "var %s=window.%s||[];%s.push({'gtm.start':new Date().getTime(),event:'gtm.js'})",
                $this->dataLayerVariable,
                $this->dataLayerVariable,
                $this->dataLayerVariable
            ))
                ->withSection(TagInterface::SECTION_HEAD)
                ->withPriority(100)
        );

        $containers = $this->containerProvider->getContainers();

        if ([] !== $containers) {
            foreach ($containers as $container) {
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
            $js = $this->writer->write($event);
        } catch (WriterException $e) {
            $this->logger->error(sprintf(
                'Could not json encode the event %s. The JSON error was: %s',
                $event::getName(),
                $e->getMessage()
            ));

            $js = sprintf("console.error('Could not json encode the event %s');", $event::getName());
        }

        $this->tagBag->add(InlineScriptTag::create($js));
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
