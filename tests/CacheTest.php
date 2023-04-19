<?php

namespace Tests;

use Mesavolt\SimpleCache;

class CacheTest extends KernelTestCase
{
    protected SimpleCache $cache;
    protected int $counter = 0;
    protected function setUp(): void
    {
        parent::setUp();
        $this->counter = 0;
        $this->cache = self::getContainer()->get(SimpleCache::class);
    }

    public function test(): void
    {
        $getCachedValue = fn() => $this->cache->get('item', fn() => ++$this->counter, 2);

        $values = [$getCachedValue(), $getCachedValue()];
        self::assertEquals([1, 1], $values);
        sleep(3);
        self::assertEquals(2, $getCachedValue());
    }
}
