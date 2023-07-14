<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Setono\GoogleAnalyticsBundle\Provider\ConfigurationBasedContainerProvider;
use Setono\GoogleAnalyticsBundle\ValueObject\Container;

/**
 * @covers \Setono\GoogleAnalyticsBundle\Provider\ConfigurationBasedContainerProvider
 */
final class ConfigurationBasedContainerProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_properties(): void
    {
        $containers = [
            ['container_id' => 'GTM-1234'],
            ['container_id' => ''],
        ];
        $provider = new ConfigurationBasedContainerProvider($containers);

        self::assertEquals([
            new Container('GTM-1234'),
        ], $provider->getContainers());
    }
}
