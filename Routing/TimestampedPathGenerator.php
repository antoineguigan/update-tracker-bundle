<?php
namespace Qimnet\UpdateTrackerBundle\Routing;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManagerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Generates timestamped paths
 * 
 */
class TimestampedPathGenerator implements TimestampedPathGeneratorInterface
{
    protected $router;
    protected $updateManager;
    protected $timestampParameterName;
    public function __construct(RouterInterface $router, UpdateManagerInterface $updateManager, $timestampParameterName)
    {
        $this->router = $router;
        $this->updateManager = $updateManager;
        $this->timestampParameterName = $timestampParameterName;
    }
    /**
     * Generates an URL containining a timestamp parameter
     * 
     * @param string $name The name of the route
     * @param array $parameters The parameters of the route
     * @param mixed $updateTrackerName The update tracker's name or an array of update trackers
     * @param type $referenceType The type of reference to be returned
     * @param type $timestampParameterName The name of the timestamp parameter
     * @return type
     */
    public function generate($name, array $parameters=array(), $updateTrackerName='global', $referenceType = RouterInterface::ABSOLUTE_PATH, $timestampParameterName=null)
    {
        if (!$timestampParameterName) {
            $timestampParameterName = $this->timestampParameterName;
        }
        $parameters[$timestampParameterName] = $this->updateManager->getLastUpdate($updateTrackerName)->format('U');
        return $this->router->generate($name, $parameters, $referenceType);
    }
}

?>
