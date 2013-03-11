<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

interface CacheManagerInterface
{
    /**
     * Returns the repository for the given type
     * 
     * @param string $name the type of the repository
     * @return CacheRepositoryInterface
     */
    public function getRepository($name=false);
    
    /**
     * Returns the stored object, or the result of the callback
     * 
     * @param string $updateTracker the tracker namespace the object is linked to
     * @param string $key the key of the object in the cache
     * @param callback $callback a callback which returns the value of the object if not present in the cache
     * @param int $ttl the ttl of the object in the cache
     * @param int $minTimestamp the minimum timestamp of the retrieved object
     * @param string $repositoryName the type of the repository
     * @return mixed the stored object
     */
    public function getObject($updateTrackerName, $key, $callback, $ttl=false, $repositoryName=false);
    
    /**
     * Remove objects from the cache
     * 
     * @param mixed contains a namespace or an array of namespaces for which objects should be removed
     * @param string $repositoryName the type of the repository
     */
    public function removeObjects($namespaces, $repositoryName=false);
}

?>
