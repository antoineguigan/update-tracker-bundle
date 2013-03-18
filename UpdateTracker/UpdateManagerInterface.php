<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

/**
 * Used to access the update tracker records
 */
interface UpdateManagerInterface
{
    /**
     * marks the given update tracker as updated
     * 
     * Marking the 'global' namespace as updated marks all the other namespaces 
     * as updated too.
     * 
     * @param string $name
     * @return array the changed update tracker entities
     */
    public function markUpdated($name);
    
    /**
     * Gets the last update time for the given namespaces 
     * 
     * @param mixed $name a namespace, or an array of namespaces
     * @return \DateTime
     */
    public function getLastUpdate($name='global');
    
    /**
     * Returns the name of the update tracker entity
     * @return name
     */
    public function getEntityName();
    
    /**
     * Returns the update tracker entity repository
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getEntityRepository();
    
}

?>
