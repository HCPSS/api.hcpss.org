<?php
/**
 * @file
 * Contains \Model\AbstractJsonModel
 */

namespace Model;

/**
 * Model stored in json format.
 */
abstract class AbstractJsonModel implements JsonModelInterface 
{    
    /**
     * The original json decoded array.
     * 
     * @var array
     */
    protected $array = [];
    
    /**
     * {@inheritDoc}
     * @see \Model\JsonModelInterface::load()
     */
    public function load(array $array) : JsonModelInterface 
    {
        $this->array = $array;
        
        return $this;
    }
    
    /**
     * Get properties of the model.
     * 
     * @param string $property
     * @return mixed
     */
    public function __get(string $property) 
    {
        return $this->array[$property];
    }
    
    /**
     * {@inheritDoc}
     * @see \Model\JsonModelInterface::getArray()
     */
    public function getArray(): array 
    {
        return $this->array;
    }
}
