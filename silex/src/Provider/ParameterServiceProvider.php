<?php
/**
 * @file
 * Contains \Provider\ParameterServiceProvider
 */

namespace Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Service\ParameterResolverService;
use Silex\Api\BootableProviderInterface;

/**
 * Resolve json:api parameters from the request.
 */
class ParameterServiceProvider implements ServiceProviderInterface, BootableProviderInterface 
{
    /**
     * @var Request
     */
    private $request;
    
    /**
     * {@inheritDoc}
     * @see \Silex\Api\BootableProviderInterface::boot()
     */
    public function boot(Application $app) 
    {
        $app->before([$this, 'setRequest'], Application::EARLY_EVENT);
    }
    
    /**
     * Set the request.
     * 
     * @param Request $request
     * @param Application $app
     */
    public function setRequest(Request $request, Application $app) 
    {
        $this->request = $request;
    }
    
    /**
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app) 
    {
        $app['json_api.param.parser'] = function ($app) {
            return new ParameterResolverService($this->request);
        };
    }
}
