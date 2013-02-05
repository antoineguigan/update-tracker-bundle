<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

use Doctrine\ORM\EntityManager;

class Repository
{
    protected $entityName;
    protected $cache=array();


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
        if (!$this->entityName) {
            throw new \RuntimeException('Could not load Update tracker entity repository');
        }
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
            $this->cache[$update->getName()] = $update->getDate();
        }
        
        return $updates;
    }
    public function getLastUpdate(EntityManager $em, $domains='global', $getGlobal=true)
    {
        $domains = (array) $domains;
        if (!in_array('global', $domains) && $getGlobal)
        {
            $domains[] = 'global';
        }
        $new = array_diff($domains, array_keys($this->cache));
        if (count($new))
        {
            $items = $em->createQuery(
                    "SELECT u FROM $this->entityName u " .
                    'WHERE u.name IN (:name)')
                    ->setParameter('name', $new)
                    ->getResult();
            foreach($items as $item)
            {
                $this->cache[$item->getName()] = $item->getDate();
            }
        }
        $cache = $this->cache;
        return array_reduce($domains, function(&$result, $domain) use ($cache) {
            return isset($cache[$domain])
                ? (is_null($result)
                    ? $cache[$domain]
                    : max($cache[$domain],$result))
                : $result;
                    
        },null);
    }
}

?>
