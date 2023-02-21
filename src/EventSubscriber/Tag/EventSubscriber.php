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

final class EventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TagBagInterface $tagBag,
        private readonly bool $consentEnabled,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['add', -1000],
        ];
    }

    public function add(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if ($this->consentEnabled) {
            $this->tagBag->add(ConsentableScriptTag::create($src, Consents::CONSENT_STATISTICS));
        } else {
            $this->tagBag->add(ScriptTag::create($src)->defer()->withSection(TagInterface::SECTION_HEAD));
        }
    }
}
