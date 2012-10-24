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
        return $this->entityName;
    }

    public function getEntityRepository(EntityManager $em)
    {
        return $em->getRepository($this->entityName);
    }

    public function markUpdated(EntityManager $em, $name)
    {
        $name = (array) $name;
        $entityName = $this->getEntityName();
        $className = $this->getEntityRepository($em)->getClassName();
        $updates = $em->createQuery("SELECT t FROM $entityName t WHERE t.name IN (:names)")
                ->setParameter('names', $name)
                ->getResult();
        $existing = array_map(function($update) { return $update->getName(); }, $updates);
        $remaining = array_diff($name, $existing);
        foreach($remaining as $name)
        {
            $update = new $className;
            $update->setName($name);
            $updates[] = $update;
        }
        foreach ($updates as $update)
        {
            $update->setDate(new \DateTime);
        }
        
        return $updates;
    }
    public function getLastUpdate(EntityManager $em, $name='global', $getGlobal=true)
    {
        $name = (array) $name;
        if (!in_array('global', $name) && $getGlobal)
        {
            $name[] = 'global';
        }
        $name[] = 'global';
        $result = $em->createQuery(
                "SELECT MAX(u.date) AS date FROM $this->entityName u " .
                'WHERE u.name IN (:name)')
                ->setParameter('name', $name)
                ->getOneOrNullResult();
        return $result ? new \DateTime($result['date']) : null;
    }
}

?>
