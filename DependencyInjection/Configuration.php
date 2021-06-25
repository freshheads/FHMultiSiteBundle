<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 * @final
 */
class Configuration implements ConfigurationInterface
{
    private const ROOT_NAME = 'fh_multi_site';

    private const ALLOWED_RESOLVERS = ['hostname_identified', 'prefixed_path_identified', 'service'];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NAME);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('repository')
                    ->isRequired()
                ->end()
                ->arrayNode('resolver')
                    ->canBeEnabled()
                    ->beforeNormalization()
                        ->ifTrue(static function (array $config) {
                            if (!$config['enabled']) {
                                return false;
                            }

                            return 'hostname_identified' === $config['type'] && !isset($config['host_mapping']);
                        })
                        ->thenInvalid('A "host_mapping" is required for the hostname_identified resolver')
                    ->end()
                    ->beforeNormalization()
                        ->ifTrue(static function (array $config) {
                            if (!$config['enabled']) {
                                return false;
                            }

                            return 'prefixed_path_identified' === $config['type'] && !isset($config['identifiers']);
                        })
                        ->thenInvalid('"identifiers" are required for the prefixed_path_identified resolver')
                    ->end()
                    ->beforeNormalization()
                        ->ifTrue(static function (array $config) {
                            if (!$config['enabled']) {
                                return false;
                            }

                            return 'service' === $config['type'] && !isset($config['service_id']);
                        })
                            ->thenInvalid('A "service_id" is required for the service resolver')
                    ->end()
                    ->children()
                        ->scalarNode('type')
                            ->cannotBeEmpty()
                            ->defaultValue('hostname_identified')
                            ->beforeNormalization()
                                ->ifNotInArray(self::ALLOWED_RESOLVERS)
                                    ->thenInvalid('Resolver type must be one of: '.implode(', ', self::ALLOWED_RESOLVERS))
                            ->end()
                        ->end()
                        ->scalarNode('service_id')->end()
                        ->arrayNode('host_mapping')
                            ->useAttributeAsKey('identifier')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('identifier')->cannotBeEmpty()->end()
                                    ->arrayNode('hostnames')
                                        ->requiresAtLeastOneElement()
                                        ->beforeNormalization()
                                            ->castToArray()
                                        ->end()
                                        ->prototype('scalar')
                                        ->end()
                                    ->end()
                                    ->arrayNode('locales')
                                        ->requiresAtLeastOneElement()
                                        ->beforeNormalization()
                                            ->castToArray()
                                        ->end()
                                        ->prototype('scalar')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('route_parameter')->defaultValue('_site')->cannotBeEmpty()->end()
                        ->scalarNode('default_identifier')->end()
                        ->arrayNode('identifiers')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()

                ->end()
            ->end();

        return $treeBuilder;
    }
}
