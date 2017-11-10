<?php
/**
 * @file
 * Contains \Service\ParameterResolverService
 */

namespace Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

/**
 * Service for resolving json:api parameters.
 */
class ParameterResolverService 
{
    /**
     * @var Request
     */
    private $request;
    
    public function __construct(Request $request) 
    {
        $this->request = $request;
    }
    
    /**
     * Parse parameters.
     * 
     * @return EncodingParametersInterface
     */
    public function parse() 
    {
        $psrFactory = new DiactorosFactory();
        $psrRequest = $psrFactory->createRequest($this->request);
        
        $factory = new Factory();
        $params = $factory->createQueryParametersParser()->parse($psrRequest);
        
        return $params;
    }
}
