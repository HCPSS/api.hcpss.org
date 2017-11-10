<?php
/**
 * @file
 * Contains \Comman\ClearCacheCommand
 */

namespace Command;

/**
 * Command to clear cache.
 */
class ClearCacheCommand
{    
    /**
     * @var string
     */
    private $cacheDir;
    
    public function __construct(string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }
    
    /**
     * Execute the command.
     */
    public function execute()
    {
        $contents = glob($this->cacheDir . '/*');
        foreach ($contents as $content) {
            $this->removeDir($content);
        }
    }
    
    /**
     * Remove directory.
     *
     * @param string $dir
     */
    private function removeDir($dir) 
    {
        if(!$dh = @opendir($dir)) return;
        
        while (false !== ($obj = readdir($dh))) {
            if($obj=='.' || $obj=='..') continue;
            if (!@unlink($dir.'/'.$obj)) self::removeDir($dir.'/'.$obj);
        }
        
        closedir($dh);
        @rmdir($dir);
    }
}
