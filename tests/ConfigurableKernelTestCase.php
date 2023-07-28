<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Tests\Kernel\TestKernel;

class ConfigurableKernelTestCase extends TestCase
{
    protected array $cacheDirs = [];

    protected function setUp(): void
    {
        $this->cacheDirs = [];
    }

    protected function tearDown(): void
    {
        $fs = new Filesystem();
        foreach ($this->cacheDirs as $cacheDir) {
            $fs->remove($cacheDir);
        }
    }

    protected function bootKernel(string $configFile): TestKernel
    {
        $kernel = new TestKernel('test', true);
        $kernel->setConfigFile($configFile);
        $kernel->boot();
        $this->cacheDirs[] = $kernel->getCacheDir();

        return $kernel;
    }
}
