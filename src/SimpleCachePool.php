<?php

namespace Mesavolt\SimpleCacheBundle;

use Mesavolt\SimpleCache;
use Psr\Container\ContainerInterface;

class SimpleCachePool
{
    private array $caches = [];

    public function registerCache(string $namespace, SimpleCache $cache): void
    {
        $this->caches[$namespace] = $cache;
    }

    public function getCache(string $namespace): SimpleCache
    {
        return $this->caches[$namespace];
    }
}
