<?php
/**
 * @file
 * Contains \Provider\EncoderServiceProvider
 */

namespace Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Factories\Factory;

/**
 * Service provider for the encoder we will use in our application.
 */
class EncoderServiceProvider implements ServiceProviderInterface 
{    
    /**
     * {@inheritDoc}
     * @see ServiceProviderInterface::register()
     */
    public function register(Container $app) 
    {
        $app['encoder'] = function ($app) {
           $factory = new Factory();
           $options = $app['debug'] ? JSON_PRETTY_PRINT : 0;
           return $factory->createEncoder(
               $app['schema.container'],
               new EncoderOptions($options, $app['app.uri'])
           );
        };
    }
}
