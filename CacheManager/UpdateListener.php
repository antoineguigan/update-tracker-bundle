<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateListenerInterface;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerInterface;

/**
 * Listens to update tracker changes, and remove cached objects corresponding to
 * the changed update tracker namespaces.
 */
class UpdateListener implements UpdateListenerInterface
{
    protected $repositories;
    
    /**
     * Constructor
     * 
     * @param \Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositories $repositories
     */
    public function __construct(CacheRepositories $repositories)
    {
        $this->repositories = $repositories;
    }
    
    /**
     * @inheritdoc
     */
    public function onUpdate(UpdateTrackerInterface $update)
    {
        $this->repositories->getRepository()->removeObjects($update->getName());
    }
}

?>
