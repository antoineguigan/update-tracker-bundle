<?php

namespace Qimnet\UpdateTrackerBundle\CacheManager;

/**
 * Collection of defined cache repositories
 */

interface CacheRepositoriesInterface
{
    /**
     * Adds a repository to the collection
     * 
     * @param string $name 
     * @param CacheRepositoryInterface $repository 
     */
    public function addRepository($name, CacheRepositoryInterface $repository);
    
    /**
     * Returns the repository corresponding to the given name
     * 
     * @param string $name
     * @return CacheRepositoryInterface
     */
    public function getRepository($name=false);
    
}

?>
