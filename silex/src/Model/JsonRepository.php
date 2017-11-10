<?php
/**
 * @file
 * Contains \Model\JsonRepository
 */

namespace Model;

use Exception\JsonRepositoryException;
use Arrch\Arrch;
use Psr\SimpleCache\CacheInterface;

/**
 * Repository for json file based models.
 */
class JsonRepository 
{
    /**
     * Type of model we are looking for.
     * 
     * @var string
     */
    private $type;
    
    /**
     * @var CacheInterface
     */
    private $cache;
    
    /**
     * @var string
     */
    private $dataDir;
    
    public function __construct(string $dataDir, string $type, CacheInterface $cache) 
    {
        $this->dataDir = $dataDir;
        $this->type = $type;
        $this->cache = $cache;
    }
    
    /**
     * Get relationship array.
     * 
     * @return array
     */
    public function getRelationships() : array
    {
        if (!$relationships = $this->cache->get('relationships')) {
            $json = file_get_contents($this->dataDir . '/schema.json');
            $relationships = json_decode($json, true);
            $this->cache->set('relationships', $relationships);
        }
                
        return $relationships['relationships'][$this->type];
    }
    
    /**
     * Loads all data of $this->type into an array.
     * 
     * @throws JsonRepositoryException
     * @return array
     */
    private function loadAll() : array 
    {
        if ($this->cache->has($this->type)) {
            return $this->cache->get($this->type);
        }
        
        $items = [];
        $directory = "{$this->dataDir}/{$this->type}";
        
        if (!file_exists($directory)) {
            throw new JsonRepositoryException("$directory not found.");
        }
        
        $directoryIterator = new \DirectoryIterator($directory);
        
        foreach ($directoryIterator as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $filepath = vsprintf('%s/%s', [
                    $fileinfo->getPath(),
                    $fileinfo->getFilename(),
                ]);
                
                $json = file_get_contents($filepath);
                $data = json_decode($json, 1);
                
                $items[] = $data;
            }
        }
        
        $this->cache->set($this->type, $items);
        return $items;
    }
    
    /**
     * Find all items.
     * 
     * @throws \Exception
     * @return JsonModelInterface[]
     */
    public function findAll() : array 
    {
        $class = self::resolveName($this->type);
        
        $items = [];
        foreach ($this->loadAll() as $data) {
            $item = new $class();
            $items->load($data);
            
            $items[] = $item;
        }
        
        return $items;
    }
    
    /**
     * Wrap an array in the appropriate model class.
     * 
     * @param array $item
     * @return JsonModelInterface
     */
    private function wrapItem(array $item) : JsonModelInterface 
    {
        $model = new JsonModel($this->type);
        $model->load($item);
        
//         $class = ClassNameResolver::resolve($this->type);
//         $class = "\\Model\\$class";
//         $model = new $class();
//         $model->load($item);
        
        return $model;
    }
    
    /**
     * Wrap items in an array in the appropriate model class.
     * 
     * @param array $items
     * @return array
     */
    private function wrapItems(array $items) : array 
    {
        $models = [];
        foreach ($items as $item) {
            $models[] = $this->wrapItem($item);
        }
        
        return $models;
    }
    
    /**
     * Query the repo. See Arrc query syntax:
     * https://github.com/rpnzl/arrch
     * 
     * @param array $options
     * @param string $key
     * @return array
     */
    public function query(array $options, $key = 'all') : array 
    {
        $results = Arrch::find($this->loadAll(), $options, $key);
        
        return $this->wrapItems($results);
    }
    
    /**
     * Find a single model by id.
     * 
     * @param mixed $id
     * @return JsonModelInterface
     */
    public function find($id) : JsonModelInterface 
    {
        if ($this->cache->has("{$this->type}.{$id}")) {
            return $this->cache->get("{$this->type}.{$id}");
        }        
        
        //$class = "\\Model\\" . ClassNameResolver::resolve($this->type);
        $filename = "{$this->dataDir}/{$this->type}/{$id}.json";
        
        if (!file_exists($filename)) {
            throw new JsonRepositoryException("$filename not found.");
        }
        
        $json = file_get_contents($filename);
        $data = json_decode($json, 1);
        
        $item = new JsonModel($this->type);
        $item->load($data);
        
        $this->cache->set("{$this->type}.{$id}", $item);
        return $item;
    }
}
