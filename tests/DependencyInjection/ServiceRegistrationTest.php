<?php

namespace Tests\DependencyInjection;


use Mesavolt\SimpleCache;
use Mesavolt\SimpleCacheBundle\SimpleCachePool;
use Tests\ConfigurableKernelTestCase;

class ServiceRegistrationTest extends ConfigurableKernelTestCase
{
    public function dataProvider_testConfigs(): array
    {
        return [
            'default' => [
                __DIR__ . '/../Resources/config/simple-cache.default.yaml',
                ['mesavolt.simple_cache.simple-cache']
            ],
            'deprecated namespace' => [
                __DIR__ . '/../Resources/config/simple-cache.deprecated-namespace.yaml',
                ['mesavolt.simple_cache.deprecated-namespace']
            ],
            'single namespace' => [
                __DIR__ . '/../Resources/config/simple-cache.single-namespace.yaml',
                ['mesavolt.simple_cache.single-namespace']
            ],
            'multiple namespaces' => [
                __DIR__ . '/../Resources/config/simple-cache.multiple-namespaces.yaml',
                ['mesavolt.simple_cache.namespace1', 'mesavolt.simple_cache.namespace2']
            ],
        ];
    }

    /** @dataProvider dataProvider_testConfigs */
    public function testConfigs(string $configFile, array $expectedServiceIds): void
    {
        $kernel = $this->bootKernel($configFile);
        $container = $kernel->getContainer();

        foreach ($expectedServiceIds as $expectedServiceId) {
            self::assertTrue($container->has($expectedServiceId));
            self::assertInstanceOf(SimpleCache::class, $container->get($expectedServiceId));
        }

        self::assertTrue($container->has(SimpleCache::class));
        self::assertInstanceOf(SimpleCache::class, $container->get(SimpleCache::class));

        self::assertTrue($container->has(SimpleCachePool::class));
        $pool = $container->get(SimpleCachePool::class);
        self::assertInstanceOf(SimpleCachePool::class, $pool);
        foreach ($expectedServiceIds as $expectedServiceId) {
            $namespace = str_replace('mesavolt.simple_cache.', '', $expectedServiceId);
            self::assertInstanceOf(SimpleCache::class, $pool->getCache($namespace));
        }
    }
}
