<?php
/**
 * @file
 * Contains \Schema\Schema
 */

namespace Schema;

use Neomerx\JsonApi\Contracts\Schema\SchemaProviderInterface;
use Neomerx\JsonApi\Schema\SchemaProvider;
use Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use Factory\JsonRepoFactory;

/**
 * Schema for JSON models.
 */
class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType;
    
    /**
     * @var JsonRepoFactory
     */
    protected $repoFactory;
    
    /**
     * An array describing relationships.
     * 
     * @var array
     */
    protected $relationships;
    
    public function __construct(SchemaFactoryInterface $factory, string $resourceType)
    {
        $this->resourceType = $resourceType;
        parent::__construct($factory);
    }
    
    /**
     * {@inheritDoc}
     * @see SchemaProviderInterface::getId()
     */
    public function getId($resource)
    {
        return $resource->getId();
    }
    
    /**
     * {@inheritDoc}
     * @see SchemaProvider::getRelationships()
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {        
        $definitions = $this->repoFactory
            ->getRepo($this->getResourceType())
            ->getRelationships();
        
        if (empty($definitions)) {
            return;
        }
        
        $relationships = [];
        foreach ($definitions as $name => $definition) {
            $relationships[$name] = [
                self::DATA => function () use ($resource, $name, $definition) {
                    $path = explode('.', $definition['map']);
                    $repo = $this->repoFactory->getRepo($definition['model']);
                    $mappedByResource = $path[0] === $resource->getType();
                    
                    switch ($definition['type']) {
                        case 'manyMany':
                            if ($mappedByResource) {
                                $related = [];
                                foreach ($resource->{$name} as $r) {
                                    $related[] = $repo->find($r['id']);
                                }                                
                            } else {
                                $where = [["{$path[1]}.{$resource->getId()}.id", $resource->getId()]];
                                $related = $repo->query(['where' => $where]);
                            }
                            break;
                        case 'hasMany':
                            $related = $repo->query(['where' => [[
                                $path[1], $resource->getId()
                            ]]]);
                            break;
                        case 'hasOne':
                            $related = $repo->find($resource->{$path[1]});
                            break;
                    }                    
                    
                    return $related;
                }, 
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
            ];
        }
        
        return $relationships;
    }
    
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
