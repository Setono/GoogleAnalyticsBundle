<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\DependencyInjection;

use Setono\GoogleAnalyticsBundle\Filter\ClientSide\ClientSideFilterInterface;
use Setono\GoogleAnalyticsEvents\Writer\Writer;
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
         * @var array{
         *     inject_library: bool,
         *     gtag: array{enabled: bool, properties: array},
         *     tag_manager: array{enabled: bool, data_layer_variable: string, containers: array},
         *     filters: array{client_side: array{paths: list<string>, hostnames: list<string>}},
         *     consent: array{enabled: bool}
         * } $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_google_analytics.inject_library', $config['inject_library']);
        $container->setParameter('setono_google_analytics.properties', $config['gtag']['properties']);
        $container->setParameter('setono_google_analytics.containers', $config['tag_manager']['containers']);
        $container->setParameter('setono_google_analytics.consent_enabled', $config['consent']['enabled']);
        $container->setParameter('setono_google_analytics.gtag_enabled', $config['gtag']['enabled']);
        $container->setParameter('setono_google_analytics.tag_manager_enabled', $config['tag_manager']['enabled']);
        $container->setParameter('setono_google_analytics.tag_manager.data_layer_variable', $config['tag_manager']['data_layer_variable']);
        $container->setParameter('setono_google_analytics.filters.client_side.paths', $config['filters']['client_side']['paths']);
        $container->setParameter('setono_google_analytics.filters.client_side.hostnames', $config['filters']['client_side']['hostnames']);

        if (true === $config['tag_manager']['enabled'] && true === $config['gtag']['enabled']) {
            throw new \InvalidArgumentException('You cannot enable both gtag and tag_manager at the same time.');
        }

        if (false === $config['tag_manager']['enabled'] && false === $config['gtag']['enabled']) {
            throw new \InvalidArgumentException('You must enable either gtag og tag_manager.');
        }

        $loader->load('services.xml');

        if (true === $config['inject_library']) {
            $loader->load('services/conditional/library_event_subscriber.xml');
        }

        if (true === $config['tag_manager']['enabled']) {
            $container->setAlias('setono_google_analytics.writer.default', 'setono_google_analytics.writer.tag_manager');

            $loader->load('services/conditional/tag_manager_collection_strategy.xml');
        }

        if (true === $config['gtag']['enabled']) {
            $container->setAlias('setono_google_analytics.writer.default', 'setono_google_analytics.writer.gtag');

            $loader->load('services/conditional/gtag_collection_strategy.xml');
        }

        $container->setAlias(Writer::class, 'setono_google_analytics.writer.default');

        if (true === $config['consent']['enabled']) {
            $loader->load('services/conditional/consent_enabled.xml');
        } else {
            $loader->load('services/conditional/consent_disabled.xml');
        }

        $container->registerForAutoconfiguration(ClientSideFilterInterface::class)
            ->addTag('setono_google_analytics.client_side_filter')
        ;
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
