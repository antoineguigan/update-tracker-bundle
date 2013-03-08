<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

/**
 * This interface should be implemented on update tracker listeners
 */
interface UpdateListenerInterface
{
    /**
     * Called when the given update tracker is changed
     * 
     * @param \Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerInterface $updateCalled
     */
    public function onUpdate(UpdateTrackerInterface $update);
}

?>
