<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\GoogleAnalyticsBundle\DependencyInjection\SetonoGoogleAnalyticsExtension;

/**
 * @covers \Setono\GoogleAnalyticsBundle\DependencyInjection\SetonoGoogleAnalyticsExtension
 */
final class SetonoGoogleAnalyticsExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new SetonoGoogleAnalyticsExtension(),
        ];
    }

    /**
     * @test
     */
    public function it_has_parameters_set(): void
    {
        $this->load([
            'gtag' => [
                'properties' => [
                    [
                        'api_secret' => 'secret1',
                        'measurement_id' => 'measurement_id1',
                    ],
                    [
                        'api_secret' => 'secret2',
                        'measurement_id' => 'measurement_id2',
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('setono_google_analytics.properties', [
            [
                'api_secret' => 'secret1',
                'measurement_id' => 'measurement_id1',
            ],
            [
                'api_secret' => 'secret2',
                'measurement_id' => 'measurement_id2',
            ],
        ]);

        $this->assertContainerBuilderHasParameter('setono_google_analytics.consent_enabled', true);
        $this->assertContainerBuilderHasParameter('setono_google_analytics.gtag_enabled', true);
        $this->assertContainerBuilderHasParameter('setono_google_analytics.tag_manager_enabled', false);
    }
}
