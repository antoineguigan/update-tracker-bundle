<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

use Doctrine\ORM\EntityManager;

/**
 * Manages an update tracker Doctrine repository
 */
class UpdateTrackerRepository implements UpdateTrackerRepositoryInterface
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
     * @inheritdoc
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @inheritdoc
     */
    public function addEventListener(UpdateListenerInterface $listener)
    {
        $this->listeners[]=$listener;
    }

    /**
     * @inheritdoc
     */
    public function getEntityRepository(EntityManager $em)
    {
        if (!$this->entityName) {
            throw new \RuntimeException('Could not load Update tracker entity repository');
        }

        return $em->getRepository($this->entityName);
    }

    /**
     * @inheritdoc
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
        foreach ($remaining as $name) {
            $update = new $className;
            $update->setName($name);
            $updates[] = $update;
        }
        foreach ($updates as $update) {
            $update->setDate(new \DateTime);
            $this->cache[$update->getName()] = $update->getDate();
            foreach ($this->listeners as $listener) {
                $listener->onUpdate($update);
            }
        }

        return $updates;
    }
    /**
     * @inheritdoc
     */
    public function getLastUpdate(EntityManager $em, $name='global', $default=true)
    {
        $name = (array) $name;
        $new = array_diff($name, array_keys($this->cache));
        if (count($new)) {
            $items = $em->createQuery(
                    "SELECT u FROM $this->entityName u " .
                    'WHERE u.name IN (:name)')
                    ->setParameter('name', $new)
                    ->getResult();
            foreach ($items as $item) {
                $this->cache[$item->getName()] = $item->getDate();
            }
        }
        $cache = $this->cache;
        $date = array_reduce($name, function(&$result, $domain) use ($cache) {
            return isset($cache[$domain])
                ? (is_null($result)
                    ? $cache[$domain]
                    : max($cache[$domain],$result))
                : $result;

        },null);
        if (is_null($date) ) {
            if ($default===true) {
                return new \DateTime;
            } else {
                return $default;
            }
        } else {
            return $date;
        }
    }
}
