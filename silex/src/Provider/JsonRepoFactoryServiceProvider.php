<?php
/**
 * @file
 * Contains \Provider\JsonRepoFactoryServiceProvider
 */

namespace Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Factory\JsonRepoFactory;

/**
 * Provides a JsonRepo factory.
 */
class JsonRepoFactoryServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['json.repo.factory'] = function ($app) {
            return new JsonRepoFactory($app['data.dir'], $app['cache']);
        };
    }
}
