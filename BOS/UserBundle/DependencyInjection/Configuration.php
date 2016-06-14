<?php

namespace BOS\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bos_user');
        $rootNode
        	->children()
                    ->variableNode("bos_login_name")->end()
                    ->variableNode("bos_user_entity")->end()
                    ->variableNode("bos_default_behaviour")->end()
                    ->variableNode("bos_system")->end()
                    ->arrayNode('roles')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('name')->end()
                                ->arrayNode('permissions')
                                    ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
        ->end();


        
        
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
