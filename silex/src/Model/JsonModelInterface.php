<?php
/**
 * @file
 * Contains \Model\JsonModelInterface
 */

namespace Model;

/**
 * Interface for models stored in json files.
 */
interface JsonModelInterface 
{   
    /**
     * Load the arrat of properties.
     * 
     * @param array $array
     * @return JsonModelInterface
     */
    public function load(array $array) : JsonModelInterface;
    
    /**
     * Conver the model to an array.
     */
    public function getArray(): array;
}
