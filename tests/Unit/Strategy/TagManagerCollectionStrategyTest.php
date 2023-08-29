<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\Strategy;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\GoogleAnalyticsBundle\Provider\ContainerProviderInterface;
use Setono\GoogleAnalyticsBundle\Strategy\TagManagerCollectionStrategy;
use Setono\GoogleAnalyticsBundle\ValueObject\Container;
use Setono\GoogleAnalyticsEvents\Event\PurchaseEvent;
use Setono\GoogleAnalyticsEvents\Writer\TagManagerWriter;
use Setono\TagBag\Renderer\ElementRenderer;
use Setono\TagBag\TagBag;

final class TagManagerCollectionStrategyTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_adds_library(): void
    {
        $tagBag = new TagBag(new ElementRenderer());
        $containerProvider = $this->prophesize(ContainerProviderInterface::class);
        $containerProvider->getContainers()->willReturn([new Container('GTM-FAD123')]);

        $strategy = new TagManagerCollectionStrategy($tagBag, $containerProvider->reveal(), new TagManagerWriter());
        $strategy->addLibrary();

        self::assertSame(
            <<<HTML
<script>var dataLayer=window.dataLayer||[];dataLayer.push({'gtm.start':new Date().getTime(),event:'gtm.js'})</script><script src="https://www.googletagmanager.com/gtm.js?id=GTM-FAD123" async></script>
HTML,
            $tagBag->renderAll()
        );
    }

    /**
     * @test
     */
    public function it_adds_event(): void
    {
        $tagBag = new TagBag(new ElementRenderer());
        $containerProvider = $this->prophesize(ContainerProviderInterface::class);

        $event = new PurchaseEvent('TRANS_1234');

        $strategy = new TagManagerCollectionStrategy($tagBag, $containerProvider->reveal(), new TagManagerWriter());
        $strategy->addEvent($event);

        self::assertSame(
            '<script>dataLayer.push({ ecommerce: null }); dataLayer.push({"ecommerce":{"transaction_id":"TRANS_1234"},"event":"purchase"});</script>',
            $tagBag->renderAll()
        );
    }
}
