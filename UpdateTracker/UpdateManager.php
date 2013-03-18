<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Used to access the update tracker records
 */
class UpdateManager implements UpdateManagerInterface
{
    protected $doctrine;
    protected $repository;

    /**
     * Constructor
     * 
     * @param Registry $doctrine
     * @param UpdateTrackerRepositoryInterface $repository
     */
    public function __construct(Registry $doctrine, UpdateTrackerRepositoryInterface $repository)
    {
        $this->doctrine = $doctrine;
        $this->repository = $repository;
    }
    
    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getLastUpdate($name='global')
    {
        return $this->repository->getLastUpdate($this->doctrine->getManager(), $name);
    }
    
    /**
     * @inheritdoc
     */
    public function getEntityName()
    {
        return $this->repository->getEntityName();
    }
    
    /**
     * @inheritdoc
     */
    public function getEntityRepository()
    {
        return $this->repository->getEntityRepository($this->doctrine->getManager());
    }
}

?>
