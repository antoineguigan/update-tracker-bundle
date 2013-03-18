<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManagerInterface;

/**
 * Used to retrieve objects from cache repositories
 */
class CacheManager implements CacheManagerInterface
{
    protected $updateManager;
    protected $repositories;

    /**
     * Constructor
     * @param UpdateManagerInterface $updateManager
     * @param CacheRepositoriesInterface $repositories
     */
    public function __construct(UpdateManagerInterface $updateManager, CacheRepositoriesInterface $repositories)
    {
        $this->updateManager = $updateManager;
        $this->repositories = $repositories;
    }
    /**
     * @inheritdoc
     */
    public function getRepository($name=false)
    {
        return $this->repositories->getRepository($name);
    }
    
    /**
     * @inheritdoc
     */
    public function getObject($updateTrackerName, $key, $callback, $ttl=false, $repositoryName=false)
    {
        return $this->getRepository($repositoryName)
                ->getObject(
                        $updateTrackerName, $key, $callback, $ttl, 
                        $this->updateManager->getLastUpdate($updateTrackerName)->format("U"));
    }
    
    /**
     * @inheritdoc
     */
    public function removeObjects($namespaces, $repositoryName=false)
    {
        $this->getRepository($repositoryName)->removeObjects($namespaces);
    }
}

?>
