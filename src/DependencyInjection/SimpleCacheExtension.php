<?php

namespace Mesavolt\SimpleCacheBundle\DependencyInjection;


use Mesavolt\SimpleCache;
use Mesavolt\SimpleCacheBundle\SimpleCachePool;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SimpleCacheExtension extends Extension
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidConfigurationException
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        // $loader->load('services.yaml');

        $configuration = new Configuration($container->getParameter('kernel.cache_dir'));
        $config = $this->processConfiguration($configuration, $configs);

        $cacheDir = $config['cache_dir'];

        $namespaces = $config['namespaces'];
        $deprecatedNamespace = $config['namespace'] ?? null;

        if ($deprecatedNamespace !== null && !in_array($deprecatedNamespace, $namespaces, true)) {
            array_unshift($namespaces, $deprecatedNamespace);
        }

        $poolDefinition = $container->register(SimpleCachePool::class);
        $poolDefinition->setPublic(true);

        foreach ($namespaces as $namespace) {
            $definition = new Definition(SimpleCache::class, [$cacheDir, $namespace]);
            $definition->setPublic(true);

            $id = "mesavolt.simple_cache.$namespace";

            $container->setDefinition($id, $definition);
            $poolDefinition->addMethodCall('registerCache', [$namespace, new Reference($id)]);
        }

        $defaultDefinition = new Definition(SimpleCache::class, [$cacheDir, $namespaces[0]]);
        $defaultDefinition->setPublic(true);
        $defaultId = SimpleCache::class;
        $container->setDefinition($defaultId, $defaultDefinition);
    }
}
