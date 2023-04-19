<?php

namespace Tests\DependencyInjection;


use Mesavolt\SimpleCache;
use Tests\KernelTestCase;

class ServiceRegistrationTest extends KernelTestCase
{
    public function testServiceRegistration(): void
    {
        $container = self::getContainer();

        $classes = [
            SimpleCache::class
        ];

        foreach ($classes as $class) {
            self::assertTrue($container->has($class));

            $service = $container->get($class);
            self::assertNotNull($service);
            self::assertInstanceOf($class, $service);
        }
    }
}
