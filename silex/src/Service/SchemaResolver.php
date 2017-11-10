<?php
/**
 * @file
 * Contains \Service\SchemResolver
 */

namespace Service;

/**
 * Resolve schemas for our application.
 */
class SchemaResolver
{
    /**
     * @var string
     */
    private $dataDir;
    
    /**
     * An array of schemas.
     * 
     * @var array
     */
    private $schemas = [];
    
    public function __construct(string $dataDir)
    {
        $this->dataDir = $dataDir;
    }
    
    /**
     * @return array
     */
    public function resolve() : array
    {
        return ["Model\\JsonModel" => "Schema\\Schema"];
    }
}
