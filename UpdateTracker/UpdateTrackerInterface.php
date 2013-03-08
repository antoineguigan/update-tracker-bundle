<?php

namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

/**
 * Update Tracker interface
 */
interface UpdateTrackerInterface
{
    /**
     * Returns the tracker namespace
     * @return string
     */
    public function getName();
    
    /**
     * Sets the tracker namespace
     * @param string $name
     */
    public function setName($name);
    
    /**
     * Returns the last update date
     * @return \DateTime
     */
    public function getDate();
    
    /**
     * Sets the last update date
     * 
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date);
}

?>
