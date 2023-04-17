<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Setono\GoogleAnalyticsBundle\Property\Property;
use Setono\GoogleAnalyticsBundle\Provider\ConfigurationBasedPropertyProvider;

/**
 * @covers \Setono\GoogleAnalyticsBundle\Provider\ConfigurationBasedPropertyProvider
 */
final class ConfigurationBasedPropertyProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_properties(): void
    {
        $properties = [
            ['api_secret' => '', 'measurement_id' => ''],
            ['api_secret' => '', 'measurement_id' => 'G-1234'],
            ['api_secret' => 's3cr3t', 'measurement_id' => ''],
            ['api_secret' => 's3cr3t', 'measurement_id' => 'G-1234'],
        ];
        $provider = new ConfigurationBasedPropertyProvider($properties);

        self::assertEquals([
            new Property('s3cr3t', 'G-1234'),
        ], $provider->getProperties());
    }
}
