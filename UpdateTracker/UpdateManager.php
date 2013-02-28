<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;
use Doctrine\Bundle\DoctrineBundle\Registry;

class UpdateManager
{
    protected $doctrine;
    protected $repository;


    public function __construct(Registry $doctrine, UpdateTrackerRepository $repository)
    {
        $this->doctrine = $doctrine;
        $this->repository = $repository;
    }
    public function markUpdated($name)
    {
        $updates = $this->repository->markUpdated($this->doctrine->getEntityManager(), $name);
        foreach($updates as $update)
        {
            $this->doctrine->getEntityManager()->persist($update);
            $this->doctrine->getEntityManager()->flush();
        }
        return $updates;
    }
    public function getLastUpdate($name='global')
    {
        return $this->repository->getLastUpdate($this->doctrine->getEntityManager(), $name);
    }
    public function getEntityName()
    {
        return $this->repository->getEntityName();
    }
    public function getEntityRepository()
    {
        return $this->repository->getEntityRepository($this->doctrine->getEntityManager());
    }
}

?>
