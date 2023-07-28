<?php

namespace Mesavolt\SimpleCacheBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public const ROOT_ALIAS = 'simple_cache';

    protected string $defaultCacheDir;

    public function __construct(string $cacheDir)
    {
        $this->defaultCacheDir = $cacheDir;
    }

    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_ALIAS);

        // symfony =< 4.1 compatibility
        // taken from https://github.com/sensiolabs/SensioFrameworkExtraBundle/pull/594/files
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('cache_dir')->defaultValue($this->defaultCacheDir)->end()
                ->arrayNode('namespaces')
                    ->fixXmlConfig('namespace')
                    ->addDefaultChildrenIfNoneSet()
                    ->scalarPrototype()
                        ->defaultValue('simple-cache')
                    ->end()
                ->end()
                ->scalarNode('namespace')
                    ->defaultValue('simple-cache')
                    ->setDeprecated('The "%node%" option is deprecated, use the "namespaces" option instead')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
