<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

/**
 * Collection of defined cache repositories
 */
class CacheRepositories
{
    protected $repositories = array();
    protected $defaultRepositoryName;
    
    /**
     * Constructor
     * 
     * @param type $defaultRepositoryName the name of the default repository
     */
    public function __construct($defaultRepositoryName)
    {
        $this->defaultRepositoryName = $defaultRepositoryName;
    }
    
    /**
     * Adds a repository to the collection
     * 
     * @param string $name 
     * @param CacheRepositoryInterface $repository 
     */
    public function addRepository($name, CacheRepositoryInterface $repository)
    {
        $this->repositories[$name] = $repository;
    }
    
    /**
     * Returns the repository corresponding to the given name
     * 
     * @param string $name
     * @return CacheRepositoryInterface
     */
    public function getRepository($name=false)
    {
        return $this->repositories[$name ? $name : $this->defaultRepositoryName];
    }
}

?>
