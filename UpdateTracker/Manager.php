<?php
namespace Qimnet\HTTPBundle\UpdateTracker;
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
        $update = $this->repository->markUpdated($this->em, $name);
        $this->em->persist($update);
        $this->em->flush();
        return $update;
    }
    public function getLastUpdate($name)
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
