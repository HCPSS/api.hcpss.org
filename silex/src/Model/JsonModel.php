<?php
/**
 * @file
 * Contains \Model\JsonModel
 */

namespace Model;

/**
 * A class for models stored as JSON.
 */
class JsonModel extends AbstractJsonModel
{
    /**
     * The model type.
     * 
     * @var string
     */
    protected $type;
    
    public function __construct(string $type)
    {
        $this->type = $type;
    }
    
    /**
     * Get the type of model.
     * 
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }
    
    /**
     * Get the identifier.
     * 
     * @return mixed
     */
    public function getId()
    {
        return $this->array['identifier'];
    }
}
