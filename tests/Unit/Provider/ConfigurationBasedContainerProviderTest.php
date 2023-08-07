<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Setono\GoogleAnalyticsBundle\Provider\ConfigurationBasedContainerProvider;
use Setono\GoogleAnalyticsBundle\ValueObject\Container;
use Setono\GoogleAnalyticsBundle\ValueObject\Property;

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
            ['container_id' => 'GTM-1234', 'property' => []],
            ['container_id' => 'GTM-1234', 'property' => ['measurement_id' => 'G-1234', 'api_secret' => 's3cr3t']],
            ['container_id' => '', 'property' => []],
        ];
        $provider = new ConfigurationBasedContainerProvider($containers);

        self::assertEquals([
            new Container('GTM-1234'),
            new Container('GTM-1234', new Property('G-1234', 's3cr3t')),
        ], $provider->getContainers());
    }
}
