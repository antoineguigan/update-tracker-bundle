<?php
namespace Qimnet\UpdateTrackerBundle\Annotation;
/**
 * Used to mark entities that should be tracked
 * 
 * @Annotation
 */
class TrackUpdate
{
    /**
     * @var mixed The name of the update tracker, or an array containing the names of the update trackers
     */
    public $name='global';
}

?>
