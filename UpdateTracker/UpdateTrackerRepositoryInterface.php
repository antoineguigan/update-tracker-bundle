<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

use Doctrine\ORM\EntityManager;

/**
 * Manages an update tracker Doctrine repository
 */
interface UpdateTrackerRepositoryInterface
{
    /**
     * Returns the name of the entity
     * 
     * @return string
     */
    public function getEntityName();
    
    /**
     * Add an event listener to the repository
     * 
     * @param \Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateListenerInterface $listener
     */
    public function addEventListener(UpdateListenerInterface $listener);
    
    /**
     * Returns the doctrine entity repository of the update tracker
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @return \Doctrine\ORM\EntityRepository
     * @throws \RuntimeException
     */
    public function getEntityRepository(EntityManager $em);
    
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
    public function markUpdated(EntityManager $em, $name);
    /**
     * Gets the last update time for the given namespaces 
     * 
     * @param EntityManager $em
     * @param mixed $domains a namespace, or an array of namespaces
     * @param boolean $getGlobal true if the global namespace should be used
     * @return \DateTime
     */
    public function getLastUpdate(EntityManager $em, $domains='global', $getGlobal=true);
}

?>
