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
        $updates = $this->repository->markUpdated($this->doctrine->getManager(), $name);
        foreach($updates as $update)
        {
            $this->doctrine->getManager()->persist($update);
            $this->doctrine->getManager()->flush();
        }
        return $updates;
    }
    public function getLastUpdate($name='global')
    {
        return $this->repository->getLastUpdate($this->doctrine->getManager(), $name);
    }
    public function getEntityName()
    {
        return $this->repository->getEntityName();
    }
    public function getEntityRepository()
    {
        return $this->repository->getEntityRepository($this->doctrine->getManager());
    }
}

?>
