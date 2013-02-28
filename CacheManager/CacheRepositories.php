<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;


class CacheRepositories
{
    protected $repositories = array();
    protected $defaultRepositoryName;
    public function addRepository($name, CacheRepositoryInterface $repository)
    {
        $this->repositories[$name] = $repository;
    }
    public function __construct($defaultRepositoryName)
    {
        $this->defaultRepositoryName = $defaultRepositoryName;
    }
    /**
     * @return CacheRepositoryInterface
     */
    public function getRepository($name=false)
    {
        return $this->repositories[$name ? $name : $this->defaultRepositoryName];
    }
}

?>
