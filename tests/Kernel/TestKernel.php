<?php

namespace Tests\Kernel;


use Mesavolt\SimpleCacheBundle\SimpleCacheBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct(string $environment = '', bool $debug = true)
    {
        parent::__construct($environment ?: $_ENV['APP_ENV'] ?? 'test', $debug);
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new SimpleCacheBundle();
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) use ($loader) {
            $this->configureContainer($container, $loader);
            $container->addObjectResource($this);
        });
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->setParameter('container.dumper.inline_class_loader', true);

        $loader->load(__DIR__.'/../Resources/config/packages/*.yaml', 'glob');
        // $loader->load(__DIR__ . '/../Resources/config/full-config.yaml');
    }

    public function getProjectDir(): string
    {
        return realpath(__DIR__.'/../..');
    }
}
