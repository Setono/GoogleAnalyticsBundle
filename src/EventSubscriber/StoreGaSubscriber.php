<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\EventSubscriber;

use Setono\GoogleAnalyticsBundle\Context\Ga\GaContextInterface;
use Setono\GoogleAnalyticsBundle\Cookie\Ga;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This subscriber will store the ga inside the _ga cookie
 */
final class StoreGaSubscriber implements EventSubscriberInterface
{
    public function __construct(private GaContextInterface $gaContext)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'store',
        ];
    }

    public function store(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $ga = $this->gaContext->getGa();

        if (!$this->setCookie($event->getRequest(), $ga)) {
            return;
        }

        $event->getResponse()->headers->setCookie($ga->asCookie());
    }

    /**
     * Returns true if the cookie should be created/updated
     */
    private function setCookie(Request $request, Ga $ga): bool
    {
        if (!$request->cookies->has('_ga')) {
            return true;
        }

        // If the creation time of the cookie is more than 2 hours ago, we will renew its expiry date
        return $ga->timestamp < (time() - 7200);
    }
}
