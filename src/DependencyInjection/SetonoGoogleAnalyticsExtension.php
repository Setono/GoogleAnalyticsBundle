<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoGoogleAnalyticsExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var array{properties: array, consent: array{enabled: bool}} $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_google_analytics.properties', $config['properties']);
        $container->setParameter('setono_google_analytics.consent_enabled', $config['consent']['enabled']);

        $loader->load('services.xml');

        if (true === $config['consent']['enabled']) {
            $loader->load('services/conditional/consent_enabled.xml');
        } else {
            $loader->load('services/conditional/consent_disabled.xml');
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('framework', [
            'messenger' => [
                'buses' => [
                    'setono_google_analytics.command_bus' => null,
                ],
            ],
        ]);
    }
}
