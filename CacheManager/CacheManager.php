<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManager;

class CacheManager
{
    protected $updateManager;
    protected $repositories;


    public function __construct(UpdateManager $updateManager, CacheRepositories $repositories)
    {
        $this->updateManager = $updateManager;
        $this->repositories = $repositories;
    }
    /**
     * @return CacheRepositoryInterface
     */
    public function getRepository($name=false)
    {
        return $this->repositories[$name ? $name : $this->defaultRepositoryName];
    }
    public function getObject($updateTrackerName, $key, $callback, $ttl=false, $repositoryName=false)
    {
        return $this->repositories->getRepository($repositoryName)
                ->getObject(
                        $updateTrackerName, $key, $callback, $ttl, 
                        $this->updateManager->getLastUpdate($updateTrackerName)->format("U"));
    }
    public function removeObjects($namespaces, $repositoryName=false)
    {
        $this->repositories->getRepository($repositoryName)->removeObjects($namespaces);
    }
}

?>
