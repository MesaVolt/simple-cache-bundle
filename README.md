# Mesavolt/SimpleCacheBundle



[![Latest Stable Version](https://poser.pugx.org/mesavolt/simple-cache-bundle/v/stable)](https://packagist.org/packages/mesavolt/simple-cache-bundle)
[![License](https://poser.pugx.org/mesavolt/simple-cache-bundle/license)](https://packagist.org/packages/mesavolt/simple-cache-bundle)


Integrate [`mesavolt/simple-cache`](https://packagist.org/packages/mesavolt/simple-cache)
into your Symfony app.

By default, it writes the cache to disk in the cache directory of Symfony (`%kernel.cache_dir%`)
and uses an empty namespace, but both these options can be configured.

## Installation

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
composer require mesavolt/simple-cache-bundle
```

That's it. Flex automagically enables the bundle for you. Go to the **Configuration**
section of this README to see how you can customize the bundle's behavior.

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require mesavolt/simple-cache-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Mesavolt\SimpleCacheBundle\SimpleCacheBundle(),
        );

        // ...
    }

    // ...
}
```

## Configuration

The following options are available to customize the behavior of the bundle's cache service :

| Option name             | Default value        | Role                               |
|-------------------------|----------------------|------------------------------------|
| simple_cache.cache_dir  | `%kernel.cache_dir%` | Where the cache is written to disk |
| simple_cache.namespaces | `simple-cache`       | Default cache namespace            |


### Applications that don't use Symfony Flex

Create `config/services/simple_cache.yaml` and tweak its content : 

```yaml
simple_cache:
    namespaces: 
      - default
      - specific
    cache_dir: /tmp/cache

```

### Applications that don't use Symfony Flex

Add this to your `app/config/config.yml` file and tweak the options :

```yaml
simple_cache:
    namespaces:
      - default
      - specific
    cache_dir: /tmp/cache
```


## Usage

To retrieve a `Mesavolt\SimpleCache` instance, you can:

- inject the cache attached to the first namespace from the `namespaces` option by type-hinting `Mesavolt\SimpleCache`
- get the `mesavolt.simple_cache.default` or `mesavolt.simple_cache.specific` services from the container,
- inject the `Mesavolt\SimpleCacheBundle\SimpleCachePool` service and get a cache by its namespace with
the `getCache($namespace)` method

```php
<?php

namespace App;


use Mesavolt\SimpleCache;
use Mesavolt\SimpleCacheBundle\SimpleCachePool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function demo1(SimpleCache $cache): Response
    {
        // $cache is the cache configured with the 'default' namespace
        $time = $cache->get('val', function () {
            return time();
        }, SimpleCache::TTL_30_MINUTES);
        
        return $this->render('home/demo.html.twig', [
            'time' => $time
        ]);
    }

    public function demo2(): Response
    {
        $time = $this->get('mesavolt.simple_cache.default')->get('val', function () {
            return time();
        }, SimpleCache::TTL_30_MINUTES);

        return $this->render('home/demo.html.twig', [
            'time' => $time
        ]);
    }

    public function demo3(SimpleCachePool $pool): Response
    {
        $time = $pool->getCache('specific')->get('val', function () {
            return time();
        }, SimpleCache::TTL_30_MINUTES);

        return $this->render('home/demo.html.twig', [
            'time' => $time
        ]);
    }
}

```
