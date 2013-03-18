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
     * @param CacheRepositoriesInterface $repositories
     */
    public function __construct(CacheRepositoriesInterface $repositories)
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
