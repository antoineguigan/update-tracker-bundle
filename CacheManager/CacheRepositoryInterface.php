<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

/**
 * Interface for cache repositories
 * 
 */
interface CacheRepositoryInterface
{
    /**
     * Returns the stored object, or the result of the callback
     * 
     * @param string $namespace the namespace of the object in the cache
     * @param string $key the key of the object in the cache
     * @param callback $callback a callback which returns the value of the object if not present in the cache
     * @param int $ttl the ttl of the object in the cache
     * @param int $minTimestamp the minimum timestamp of the retrieved object
     * @return mixed the stored object
     */
    public function getObject($namespace, $key, $callback, $ttl=false, $minTimestamp=false);
    
    /**
     * Remove objects from the cache
     * 
     * @param mixed contains a namespace or an array of namespaces for which objects should be removed
     */
    public function removeObjects($namespaces);
}

?>
