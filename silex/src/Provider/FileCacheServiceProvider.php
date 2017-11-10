<?php
/**
 * @file
 * Contains \Provider\FileCacheServiceProvider
 */

namespace Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * Provides a file based cache.
 */
class FileCacheServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['cache'] = function ($app) {
            return new FilesystemCache('', 0, $app['cache.dir']);
        };
    }
}
