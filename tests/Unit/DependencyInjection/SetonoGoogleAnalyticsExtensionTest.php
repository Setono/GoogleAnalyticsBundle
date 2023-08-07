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
            'filters' => [
                'client_side' => [
                    'paths' => [
                        '/admin',
                    ],
                    'hostnames' => [
                        '*.github.com',
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
        $this->assertContainerBuilderHasParameter('setono_google_analytics.filters.client_side.paths', ['/admin']);
        $this->assertContainerBuilderHasParameter('setono_google_analytics.filters.client_side.hostnames', ['*.github.com']);
    }

    /**
     * @test
     */
    public function it_enables_gtag_with_null_value(): void
    {
        $this->load([
            'gtag' => null,
        ]);

        $this->assertContainerBuilderHasParameter('setono_google_analytics.gtag_enabled', true);
        $this->assertContainerBuilderHasParameter('setono_google_analytics.tag_manager_enabled', false);
    }

    /**
     * @test
     */
    public function it_enables_tag_manager_with_no_associated_property(): void
    {
        $this->load([
            'tag_manager' => [
                'containers' => [
                    ['id' => 'container_id'],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('setono_google_analytics.tag_manager_enabled', true);
        $this->assertContainerBuilderHasParameter('setono_google_analytics.containers', [
            ['id' => 'container_id', 'property' => []],
        ]);
    }

    /**
     * @test
     */
    public function it_enables_tag_manager_with_associated_property(): void
    {
        $this->load([
            'tag_manager' => [
                'containers' => [
                    ['id' => 'container_id', 'property' => ['measurement_id' => 'G-1234', 'api_secret' => 's3cr3t']],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('setono_google_analytics.tag_manager_enabled', true);
        $this->assertContainerBuilderHasParameter('setono_google_analytics.containers', [
            ['id' => 'container_id', 'property' => ['measurement_id' => 'G-1234', 'api_secret' => 's3cr3t']],
        ]);
    }

    /**
     * @test
     */
    public function it_enables_tag_manager_with_null_value(): void
    {
        $this->load([
            'tag_manager' => null,
        ]);

        $this->assertContainerBuilderHasParameter('setono_google_analytics.gtag_enabled', false);
        $this->assertContainerBuilderHasParameter('setono_google_analytics.tag_manager_enabled', true);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_both_gtag_and_tag_manager_is_enabled(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->load([
            'gtag' => null,
            'tag_manager' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_both_gtag_and_tag_manager_is_disabled(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->load([
            'gtag' => false,
            'tag_manager' => false,
        ]);
    }
}
