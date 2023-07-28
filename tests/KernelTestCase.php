<?php

namespace Tests;


use Symfony\Component\Filesystem\Filesystem;
use Tests\Kernel\TestKernel;

class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function tearDown(): void
    {
        self::ensureKernelShutdown();

        $fs = new Filesystem();
        $fs->remove(self::$kernel->getCacheDir());
    }
}
