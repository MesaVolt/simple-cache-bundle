<?php

namespace DependencyInjection;


use Mesavolt\SimpleCache;
use Mesavolt\SimpleCacheBundle\SimpleCachePool;
use Tests\ConfigurableKernelTestCase;

class NamespaceSeparationTest extends ConfigurableKernelTestCase
{
    private function assertCacheSeparated(SimpleCache $cache1, SimpleCache $cache2): void
    {
        $cache1->set('key', 'cache1', SimpleCache::TTL_1_YEAR);
        $cache2->set('key', 'cache2', SimpleCache::TTL_1_YEAR);

        self::assertEquals('cache1', $cache1->read('key'));
        self::assertEquals('cache2', $cache2->read('key'));

        $cache1->clear();

        self::assertNull($cache1->read('cache'));
        self::assertEquals('cache2', $cache2->read('key'));
    }

    public function testNamespacesAreSeparatedInContainer(): void
    {
        $kernel = $this->bootKernel(__DIR__.'/../Resources/config/simple-cache.multiple-namespaces.yaml');
        $container = $kernel->getContainer();

        /** @var SimpleCache $cache1 */
        $cache1 = $container->get('mesavolt.simple_cache.namespace1');
        /** @var SimpleCache $cache2 */
        $cache2 = $container->get('mesavolt.simple_cache.namespace2');

        $this->assertCacheSeparated($cache1, $cache2);
    }

    public function testNamespacesAreSeparatedInPool(): void
    {
        $kernel = $this->bootKernel(__DIR__ . '/../Resources/config/simple-cache.multiple-namespaces.yaml');
        $container = $kernel->getContainer();

        /** @var SimpleCachePool $pool */
        $pool = $container->get(SimpleCachePool::class);

        $cache1 = $pool->getCache('namespace1');
        $cache2 = $pool->getCache('namespace2');

        $this->assertCacheSeparated($cache1, $cache2);
    }
}
