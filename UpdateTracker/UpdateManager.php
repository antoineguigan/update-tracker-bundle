<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Used to access the update tracker records
 */
class UpdateManager
{
    protected $doctrine;
    protected $repository;

    /**
     * Constructor
     * 
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param \Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerRepository $repository
     */
    public function __construct(Registry $doctrine, UpdateTrackerRepository $repository)
    {
        $this->doctrine = $doctrine;
        $this->repository = $repository;
    }
    
    /**
     * marks the given update tracker as updated
     * 
     * Marking the 'global' namespace as updated marks all the other namespaces 
     * as updated too.
     * 
     * @param string $name
     * @return array the changed update tracker entities
     */
    public function markUpdated($name)
    {
        $updates = $this->repository->markUpdated($this->doctrine->getManager(), $name);
        foreach($updates as $update)
        {
            $this->doctrine->getManager()->persist($update);
            $this->doctrine->getManager()->flush();
        }
        return $updates;
    }
    
    /**
     * Gets the last update time for the given namespaces 
     * 
     * @param mixed $name a namespace, or an array of namespaces
     * @return \DateTime
     */
    public function getLastUpdate($name='global')
    {
        return $this->repository->getLastUpdate($this->doctrine->getManager(), $name);
    }
    
    /**
     * Returns the name of the update tracker entity
     * @return name
     */
    public function getEntityName()
    {
        return $this->repository->getEntityName();
    }
    
    /**
     * Returns the update tracker entity repository
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getEntityRepository()
    {
        return $this->repository->getEntityRepository($this->doctrine->getManager());
    }
}

?>
