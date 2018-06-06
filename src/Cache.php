<?php

namespace Mesavolt\SimpleCacheBundle;


class Cache
{
    protected $namespace;
    protected $cacheDir;

    public function __construct(string $cacheDir, string $namespace = '')
    {
        $this->cacheDir = $cacheDir;
        $this->namespace = $namespace;
    }
}
