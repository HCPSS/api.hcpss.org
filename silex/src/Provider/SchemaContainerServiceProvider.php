<?php
/**
 * @file
 * Contains \Provider\SchemaContainerServiceProvider
 */

namespace Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Neomerx\JsonApi\Factories\Factory;
use Schema\SchemaContainer;
use Neomerx\JsonApi\Factories\ProxyLogger;

/**
 * Schema container service.
 */
class SchemaContainerServiceProvider implements ServiceProviderInterface 
{
    /**
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app) 
    {
        $app['schema.container'] = function ($app) {
            $schemas = $app['schema'];
            $factory = new Factory();
            
            $container = new SchemaContainer($factory, $schemas);
            $container->setLogger(new ProxyLogger());
            $container->setContainer($app);
            
            return $container;
        };
    }
}
