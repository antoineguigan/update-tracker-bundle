<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;
use Doctrine\ORM\EntityManager;

class Manager
{
    protected $em;
    protected $repository;


    public function __construct(EntityManager $em, Repository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }
    public function markUpdated($name)
    {
        $updates = $this->repository->markUpdated($this->em, $name);
        foreach($updates as $update)
        {
            $this->em->persist($update);
            $this->em->flush();
        }
        return $updates;
    }
    public function getLastUpdate($name='global')
    {
        return $this->repository->getLastUpdate($this->em, $name);
    }
    public function getEntityName()
    {
        return $this->repository->getEntityName();
    }
    public function getEntityRepository()
    {
        return $this->repository->getEntityRepository($this->em);
    }
}

?>
