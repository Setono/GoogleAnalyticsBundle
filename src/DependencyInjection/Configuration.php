<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_google_analytics');
        $rootNode = $treeBuilder->getRootNode();

        /** @psalm-suppress MixedMethodCall, PossiblyUndefinedMethod, PossiblyNullReference */
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('properties')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('api_secret')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('measurement_id')->isRequired()->cannotBeEmpty()->end()
        ;

        return $treeBuilder;
    }
}
