<?php

namespace Tests;

use Mesavolt\SimpleCache;
use Mesavolt\SimpleCacheBundle\SimpleCachePool;

class SimpleCachePoolTest extends KernelTestCase
{
    public function testPoolUsesSameServiceAsContainer(): void
    {
        $container = self::getContainer();

        /** @var SimpleCachePool $pool */
        $pool = $container->get(SimpleCachePool::class);
        $cacheFromPool = $pool->getCache('simple-cache');

        /** @var SimpleCache $cacheFromContainer */
        $cacheFromContainer = $container->get(SimpleCache::class);

        $cacheFromContainer->set('keyFromContainer', 'valueFromContainer');
        self::assertEquals('valueFromContainer', $cacheFromPool->read('keyFromContainer'));

        $cacheFromPool->set('keyFromPool', 'valueFromPool');
        self::assertEquals('valueFromPool', $cacheFromContainer->read('keyFromPool'));
    }
}
