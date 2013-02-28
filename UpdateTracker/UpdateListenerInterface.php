<?php
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

interface UpdateListenerInterface
{
    public function onUpdate(UpdateTrackerInterface $update);
}

?>
