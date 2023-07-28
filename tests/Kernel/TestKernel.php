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

    private string $configFile;
    private string $cacheDir;

    public function __construct(string $environment = 'test', bool $debug = true)
    {
        parent::__construct($environment, $debug);

        $this->configFile = __DIR__.'/../Resources/config/simple-cache.default.yaml';
        $this->cacheDir = uniqid("$this->environment-", true);
    }

    public function setConfigFile(string $configFile): void
    {
        $this->configFile = $configFile;
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

        $loader->load(__DIR__.'/../Resources/config/packages/framework.yaml', 'yaml');
        $loader->load($this->configFile);
    }

    public function getProjectDir(): string
    {
        return realpath(__DIR__.'/../..');
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . "/var/cache/$this->cacheDir";
    }
}
