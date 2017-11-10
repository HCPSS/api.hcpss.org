<?php
/**
 * @file
 * Contains \Provider\SchemaResolverServiceProvider
 */

namespace Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Service\SchemaResolver;

/**
 * Provides a schema resolver.
 */
class SchemaResolverServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app) 
    {
        $app['schema'] = function ($app) {
            $resolver = new SchemaResolver($app['data.dir']);
            
            return $resolver->resolve();
        };
    }
}
