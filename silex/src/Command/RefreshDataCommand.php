<?php
/**
 * @file
 * Contains \Command\RefreshDataCommand
 */

namespace Command;

/**
 * A Command to fetch the remote api data and store it locally.
 */
class RefreshDataCommand 
{    
    private $apiUri = 'https://api.hocoschools.org';
    
    /**
     * @var string
     */
    private $dataDir;
    
    public function __construct(string $dataDir)
    {
        $this->dataDir = $dataDir; 
    }
    
    /**
     * Refresh administrative clusters.
     * 
     * @return RefreshDataCommand
     */
    public function boeClusters() : RefreshDataCommand
    {
        $clusters = $this->fetch('boe_clusters');
        $this->setIds($clusters, 'cluster');        
        $this->write($clusters, 'boe_cluster', 'cluster');
        
        return $this;
    }
    
    /**
     * Refresh administrative clusers.
     * 
     * @return RefreshDataCommand
     */
    public function administrativeClusters() : RefreshDataCommand
    {
        $clusters = $this->fetch('administrative_clusters');
        $this->setIds($clusters, 'cluster');
        $this->write($clusters, 'administrative_cluster', 'cluster');
        
        return $this;
    }
    
    /**
     * Refresh achievements.
     * 
     * @return RefreshDataCommand
     */
    public function achievements() : RefreshDataCommand
    {
        $facilities = $this->fetch('schools');
        
        $achievements = [];
        foreach ($facilities['schools'] as $acronyms) {
            foreach ($acronyms as $acronym) {
                $facility = $this->fetch("schools/{$acronym}");
                
                foreach ($facility['achievements'] as $achievement) {
                    $achievements[$achievement['machine_name']] = [
                        'machine_name' => $achievement['machine_name'],
                        'name' => $achievement['name'],
                    ];
                }
            }
        }
        
        $this->setIds($achievements, 'machine_name');
        $this->write($achievements, 'achievement', 'machine_name');
        
        return $this;
    }
    
    /**
     * Fetch a facility,
     * 
     * @param string $acronym
     * @return array
     */
    private function fetchFacility(string $acronym) : array
    {
        $facility = $this->fetch("schools/{$acronym}");
        
        $boeCluster = $facility['boe_cluster']['cluster'];
        $facility['boe_cluster'] = $boeCluster;
        
        $administrativeCluster = $facility['administrative_cluster']['cluster'];
        $facility['administrative_cluster'] = $administrativeCluster;
        
        $facility['achievements'] = $this->trimAchievements($facility['achievements']);
        
        return $facility;
    }
    
    /**
     * Trim achievements to just the direct attributes.
     * 
     * @param array $achievements
     * @return array
     */
    private function trimAchievements(array $achievements) : array
    {
        $blacklist = ['machine_name', 'name', 'url'];
        $newAchievements = [];
        foreach ($achievements as $achievement) {
            $newAchievement['id'] = $achievement['machine_name'];

            foreach ($achievement as $key => $value) {
                if (!in_array($key, $blacklist)) {
                    $newAchievement[$key] = $value;
                }
            }

            $newAchievements[$achievement['machine_name']] = $newAchievement;
        }
        
        return $newAchievements;
    }
    
    /**
     * Refresh facilities.
     */
    public function facilities() : RefreshDataCommand
    {
        $facilities = $this->fetch('schools');
        
        $data = [];
        foreach ($facilities['schools'] as $acronyms) {
            foreach ($acronyms as $acronym) {
                $data[] = $this->fetchFacility($acronym);
            }
        }
        
        $this->setIds($data, 'acronym');
        $this->write($data, 'facility', 'acronym');
        
        return $this;
    }
    
    /**
     * Fetch data from the api.
     * 
     * @param string $path
     * @return array
     */
    private function fetch(string $path) : array
    {
        $uri = "{$this->apiUri}/{$path}.json";
        return json_decode(file_get_contents($uri), 1);
    }
    
    /**
     * Make sure the resources have an id.
     * 
     * @param array $resources
     * @param string $identifier
     */
    private function setIds(array &$resources, string $identifier) 
    {
        foreach ($resources as &$resource) {
            $resource = ['identifier' => $resource[$identifier]] + $resource;
        }
    }
    
    /**
     * Write the items to the json file.
     *
     * @param array $items
     * @param string $path
     * @param string $id
     */
    private function write(array $items, string $path, string $id)
    {
        foreach ($items as $item) {
            $data = json_encode($item, JSON_PRETTY_PRINT);
            $file = $this->dataDir . "/$path/" . $item[$id] . '.json';
            file_put_contents($file, $data . "\n");
        }
    }
}
