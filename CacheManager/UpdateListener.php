<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateListenerInterface;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerInterface;

class UpdateListener implements UpdateListenerInterface
{
    protected $repositories;
    
    public function __construct(CacheRepositories $repositories)
    {
        $this->repositories = $repositories;
    }
    public function onUpdate(UpdateTrackerInterface $update)
    {
        $this->repositories->getRepository()->removeObjects($update->getName());
    }
}

?>
