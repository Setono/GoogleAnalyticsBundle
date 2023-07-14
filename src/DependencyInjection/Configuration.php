<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\DependencyInjection;

use Setono\ConsentBundle\SetonoConsentBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_google_analytics');
        $rootNode = $treeBuilder->getRootNode();

        /** @psalm-suppress MixedMethodCall, PossiblyNullReference,UndefinedInterfaceMethod */
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('inject_library')
                    ->defaultTrue()
                    ->info('If this is set to true, the bundle will inject the library for the corresponding collection strategy, e.g. if you have enabled "tag_manager", it will inject the https://www.googletagmanager.com/gtm.js library')
                ->end()
                ->arrayNode('gtag')
                    ->canBeEnabled()
                    ->children()
                        ->arrayNode('properties')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('measurement_id')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('api_secret')->defaultNull()->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('tag_manager')
                    ->canBeEnabled()
                    ->children()
                        ->arrayNode('containers')
                            ->info('Notice that adding more than one container is possible, but not recommended as per Googles best practices')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('id')
                                        ->info('The container id. Looks something like: GTM-WMF5KF')
                                        ->isRequired()
                                        ->cannotBeEmpty()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('filters')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('client_side')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('paths')
                                    ->scalarPrototype()
                                        ->info('A path to exclude from client side tracking. You can use glob matching (see https://github.com/webmozarts/glob), regular expressions (must start with #) or exact matching. If you want to exclude your admin area located at /admin, you could input #/admin(/.*)?# here.')
                                        ->isRequired()
                                        ->cannotBeEmpty()
                                    ->end()
                                ->end()
                                ->arrayNode('hostnames')
                                    ->scalarPrototype()
                                        ->info('A hostname to exclude from client side tracking. You can use fn matching (see https://www.php.net/manual/en/function.fnmatch.php), regular expressions (must start with #) or exact matching. If you want to exclude all subdomains for example.com you would add *.example.com.')
                                        ->isRequired()
                                        ->cannotBeEmpty()
        ;

        $consentNode = $rootNode->children()->arrayNode('consent');

        if (class_exists(SetonoConsentBundle::class)) {
            $consentNode->canBeDisabled();
        } else {
            $consentNode->canBeEnabled();
        }

        return $treeBuilder;
    }
}
