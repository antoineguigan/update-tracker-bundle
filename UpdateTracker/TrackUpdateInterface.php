<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

/**
 * Implement this interface on an entity to track its updates
 */
interface TrackUpdateInterface
{
    /**
     * Returns an update tracker namespace or an array of update tracker namespaces
     * 
     * @return mixed
     */
    public function getUpdateTrackerName();
}

?>
