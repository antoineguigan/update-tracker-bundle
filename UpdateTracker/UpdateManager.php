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
        $updates = $this->repository->markUpdated($this->getManager(), $name);
        foreach($updates as $update)
        {
            $this->getManager()->persist($update);
            $this->getManager()->flush();
        }
        return $updates;
    }
    
    /**
     * @inheritdoc
     */
    public function getLastUpdate($name='global', $default=true)
    {
        return $this->repository->getLastUpdate($this->getManager(), $name, $default);
    }
    
    /**
     * @inheritdoc
     */
    public function getEntityName()
    {
        return $this->repository->getEntityName();
    }
    protected function getManager()
    {
        return $this->doctrine->getManagerForClass($this->getEntityName());
    }

    /**
     * @inheritdoc
     */
    public function getEntityRepository()
    {
        return $this->repository->getEntityRepository($this->getManager());
    }
}

?>
