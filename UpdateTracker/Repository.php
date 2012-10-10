<?php
namespace Qimnet\HTTPBundle\UpdateTracker;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class Repository
{
    protected $entityName;
    
    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }
    public function getEntityName()
    {
        return $this->entityName();
    }

    public function getEntityRepository(EntityManager $em)
    {
        return $em->getRepository($this->entityName);
    }

    public function markUpdated(EntityManager $em, $name)
    {
        $repository = $this->getEntityRepository($em);
        $update = $repository->findOneByName($name);
        if (!$update)
        {
            $class = $repository->getClassName();
            $update = new $class;
            $update->setName($name);
        }
        $update->setDate(new \DateTime);
        return $update;
    }
    public function getLastUpdate(EntityManager $em, $name)
    {
        $result = $em->createQuery(
                "SELECT u.date FROM $this->entityName u " .
                'WHERE u.name=:name')
                ->setParameter('name', $name)
                ->getOneOrNullResult(Query::HYDRATE_ARRAY);
        return count($result) ? $result['date'] : new \DateTime;
    }
}

?>
