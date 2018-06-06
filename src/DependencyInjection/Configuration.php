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

    protected $defaultCacheDir;

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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(self::ROOT_ALIAS);

        $rootNode
            ->children()
                ->scalarNode('cache_dir')->defaultValue($this->defaultCacheDir)->end()
                ->scalarNode('namespace')->defaultValue('simple-cache')->end()
            ->end();

        return $treeBuilder;
    }
}
