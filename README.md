# Mesavolt/SimpleCacheBundle

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

| Option name            | Default value        | Role                               |
| ---------------------- | -------------------- | -----------------------------------|
| simple_cache.cache_dir | `%kernel.cache_dir%` | Where the cache is written to disk |
| simple_cache.namespace | `simple-cache`       | Default cache namespace            |


### Applications that don't use Symfony Flex

Create `config/services/simple_cache.yaml` and tweak its content : 

```yaml
simple_cache:
    namespace: mca-cache
    cache_dir: /tmp/cache

```

### Applications that don't use Symfony Flex

Add this to your `app/config/config.yml` file and tweak the options :

```yaml
simple_cache:
    namespace: mca-cache
    cache_dir: /tmp/cache
```


## Usage

Inject the `Mesavolt\SimpleCache` service into your services and controllers
(or get the `mesavolt.simple_cache` service from the container) :

```php
<?php

namespace App;


use Mesavolt\SimpleCache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(SimpleCache $cache)
    {
        $time = $cache->get('val', function () {
            return time();
        }, SimpleCache::TTL_30_MINUTES);
        
        return $this->render('home/index.html.twig', [
            'time' => $time
        ]);
    }
}

```
