<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

use Doctrine\ORM\EntityManager;

/**
 * Manages an update tracker Doctrine repository
 */
class UpdateTrackerRepository
{
    protected $entityName;
    protected $cache=array();
    protected $listeners=array();

    /**
     * Constructor
     * 
     * @param string $entityName the name of the entity
     */
    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }
    
    /**
     * Returns the name of the entity
     * 
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Add an event listener to the repository
     * 
     * @param \Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateListenerInterface $listener
     */
    public function addEventListener(UpdateListenerInterface $listener)
    {
        $this->listeners[]=$listener;
    }

    /**
     * Returns the doctrine entity repository of the update tracker
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @return \Doctrine\ORM\EntityRepository
     * @throws \RuntimeException
     */
    public function getEntityRepository(EntityManager $em)
    {
        if (!$this->entityName) {
            throw new \RuntimeException('Could not load Update tracker entity repository');
        }
        return $em->getRepository($this->entityName);
    }

    /**
     * marks the given update tracker as updated
     * 
     * Marking the 'global' namespace as updated marks all the other namespaces 
     * as updated too.
     * 
     * @param EntityManager $em
     * @param string $name
     * @return array the changed update tracker entities
     */
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
            foreach($this->listeners as $listener) {
                $listener->onUpdate($update);
            }
        }
        
        return $updates;
    }
    /**
     * Gets the last update time for the given namespaces 
     * 
     * @param EntityManager $em
     * @param mixed $domains a namespace, or an array of namespaces
     * @param boolean $getGlobal true if the global namespace should be used
     * @return \DateTime
     */
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
                    
        },$getGlobal ? new \DateTime : null);
    }
}

?>
