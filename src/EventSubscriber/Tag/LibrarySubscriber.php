<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber\Tag;

use Setono\Consent\Consents;
use Setono\GoogleAnalyticsBundle\Provider\PropertyProviderInterface;
use Setono\TagBag\Tag\ConsentableScriptTag;
use Setono\TagBag\Tag\InlineScriptTag;
use Setono\TagBag\Tag\ScriptTag;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\TagBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class LibrarySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TagBagInterface $tagBag,
        private readonly PropertyProviderInterface $propertyProvider,
        private readonly bool $consentEnabled,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'add',
        ];
    }

    public function add(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        // By adding this javascript to all pages no matter if we have any properties we make it easier for ourselves
        // when adding tags in other subscribers, since they will be using the gtag method, and therefore we don't
        // have to check if there are any properties when adding all other tags
        $this->tagBag->add(InlineScriptTag::create('function gtag(){dataLayer.push(arguments)}window.dataLayer=window.dataLayer||[],gtag("js",new Date);')
            ->withSection(TagInterface::SECTION_HEAD));

        $properties = $this->propertyProvider->getProperties();
        if (count($properties) > 0) {
            $this->tagBag->add(InlineScriptTag::create(sprintf('gtag("config", "%s");', $properties[0]->measurementId))
                ->withSection(TagInterface::SECTION_HEAD))
            ;

            $src = sprintf('https://www.googletagmanager.com/gtag/js?id=%s', $properties[0]->measurementId);

            if ($this->consentEnabled) {
                $this->tagBag->add(ConsentableScriptTag::create($src, Consents::CONSENT_STATISTICS));
            } else {
                $this->tagBag->add(ScriptTag::create($src)->defer()->withSection(TagInterface::SECTION_HEAD));
            }
        } else {
            $this->tagBag->add(InlineScriptTag::create('console.error("[Setono Google Analytics Bundle] You have not configured any Google Analytics properties.")'));
        }
    }
}
