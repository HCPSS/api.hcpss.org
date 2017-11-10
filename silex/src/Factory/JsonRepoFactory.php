<?php
/**
 * @file
 * Contains \Factory\JsonRepoFactory
 */

namespace Factory;

use Model\JsonRepository;
use Psr\SimpleCache\CacheInterface;

/**
 * Factory for JsonRepository.
 */
class JsonRepoFactory
{
    /**
     * @var JsonRepository[]
     */
    private $repos = [];
    
    /**
     * @var CacheInterface
     */
    private $cache;
    
    /**
     * @var string
     */
    private $dataDir;
    
    public function __construct(string $dataDir, CacheInterface $cache)
    {
        $this->dataDir = $dataDir;
        $this->cache = $cache;
    }
    
    /**
     * Get a JsonRepository.
     * 
     * @param string $type
     * @return JsonRepository
     */
    public function getRepo(string $type) : JsonRepository
    {
        if (!array_key_exists($type, $this->repos)) {
            $this->repos[$type] = new JsonRepository($this->dataDir, $type, $this->cache);
        }
        
        return $this->repos[$type];
    }
}
