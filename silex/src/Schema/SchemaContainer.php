<?php
/**
 * @file
 * Contains \Schema\SchemaContainer
 */

namespace Schema;

use Neomerx\JsonApi\Schema\Container as JsonApiContainer;
use Pimple\Container;

/**
 * Schema container for JSON mode schemas.
 */
class SchemaContainer extends JsonApiContainer
{
    /**
     * @var Container
     */
    private $container;
    
    /**
     * Set the container.
     * 
     * @param Container $container
     * @return SchemaContainer
     */
    public function setContainer(Container $container) : SchemaContainer
    {
        $this->container = $container;
        
        return $this;
    }
    
    /**
     * Get the container.
     * 
     * @return \Pimple\Container
     */
    public function getContainer()
    {
        return $this->container;
    }
    
    /**
     * @inheritdoc
     */
    public function getSchemaByType($type)
    {
        $schema = new Schema($this->getFactory(), $type);
        $schema->setRepoFactory($this->container['json.repo.factory']);
        
        return $schema;
    }
    
    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getSchemaByResourceType($resourceType)
    {
        $schema = new Schema($this->getFactory(), $resourceType);
        $schema->setRepoFactory($this->container['json.repo.factory']);
        
        return $schema;
    }
    
    /**
     * @param object $resource
     *
     * @return string
     */
    protected function getResourceType($resource)
    {
        return $resource->getType();
    }
}
