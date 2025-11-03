<?php

namespace AppVerk\UserBundle\DependencyInjection;

use AppVerk\Components\Model\UserInterface;
use AppVerk\UserBundle\Security\SimpleAccessResolver;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('app_verk_app_user');
        
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('app_verk_app_user', 'array');
        }
        
        $children = $root->children();

        $children
            ->scalarNode('default_role')
                ->defaultValue(UserInterface::ROLE_DEFAULT)
            ->end()
        ;

        $this->addEntitiesConfig($children);
        $this->addAclConfig($children);
        $children->end();

        return $treeBuilder;
    }

    private function addEntitiesConfig(NodeBuilder $rootNode)
    {
        $rootNode
            ->arrayNode('entities')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('user_class')->cannotBeEmpty()->end()
                ->end()
            ->end()
        ;
    }

    private function addAclConfig(NodeBuilder $rootNode)
    {
        $rootNode
            ->arrayNode('acl')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultFalse()->end()
                    ->scalarNode('access_resolver_class')->defaultValue(SimpleAccessResolver::class)->end()
                    ->scalarNode('access_denied_path')->defaultNull()->end()
                    ->booleanNode('access_denied_show_flash_message')->defaultFalse()->end()
                ->end()
            ->end()
        ;
    }
}
