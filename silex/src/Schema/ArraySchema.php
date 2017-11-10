<?php
/**
 * @file
 * Contains \Schema\ArraySchema
 */

namespace Schema;

use Neomerx\JsonApi\Schema\SchemaProvider;
use Neomerx\JsonApi\Contracts\Schema\SchemaProviderInterface;
use Factory\JsonRepoFactory;

/**
 * Schema for models that store their attributes in an internal array.
 */
abstract class ArraySchema extends SchemaProvider 
{    
    /**
     * @var JsonRepoFactory
     */
    protected $repoFactory;
    
    /**
     * {@inheritDoc}
     * @see SchemaProviderInterface::getAttributes()
     */
    public function getAttributes($model) {        
        return $model->getArray();
    }
    
    /**
     * Set the data directory.
     * 
     * @param string $dataDir
     * @return SchemaProviderInterface
     */
    public function setRepoFactory(JsonRepoFactory $factory) : SchemaProviderInterface
    {
        $this->repoFactory = $factory;
        
        return $this;
    }
}
