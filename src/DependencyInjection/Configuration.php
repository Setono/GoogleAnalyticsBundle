<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\DependencyInjection;

use Setono\Consent\Consent;
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

        $consentNode = $rootNode->children()->arrayNode('consent');

        if (class_exists(Consent::class)) {
            $consentNode->canBeDisabled();
        } else {
            $consentNode->canBeEnabled();
        }

        return $treeBuilder;
    }
}
