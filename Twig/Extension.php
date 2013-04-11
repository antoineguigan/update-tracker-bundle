<?php
namespace Qimnet\UpdateTrackerBundle\Twig;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManagerInterface;
use Qimnet\UpdateTrackerBundle\Routing\TimestampedPathGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Extension extends \Twig_Extension
{
    protected $updateManager;
    protected $pathGenerator;

    public function __construct(UpdateManagerInterface $updateManager, 
            TimestampedPathGeneratorInterface $pathGenerator)
    {
        $this->updateManager = $updateManager;
        $this->pathGenerator = $pathGenerator;
    }
    public function getName()
    {
       return 'update_tracker';
    }
    public function getFunctions()
    {
        return array(
            'timestamped_path'=>new \Twig_Function_Method($this, "timestampedPath"),
            'timestamped_url'=>new \Twig_Function_Method($this, "timestampedUrl"),
            'timestamped_controller'=>new \Twig_Function_Method($this, "timestampedController")
            );
    }

    public function timestampedPath($name, $parameters = array(), $updateTrackerName='global', $relative = false, $timestampParameterName=null)
    {
        return  $this->pathGenerator->generate($name, $parameters, $updateTrackerName, $relative ? RouterInterface::RELATIVE_PATH : RouterInterface::ABSOLUTE_PATH, $timestampParameterName);
    }
    public function timestampedUrl($name, $parameters = array(), $updateTrackerName='global', $schemeRelative = false, $timestampParameterName=null)
    {
        return  $this->pathGenerator->generate($name, $parameters, $updateTrackerName, $schemeRelative ? RouterInterface::NETWORK_PATH : RouterInterface::ABSOLUTE_URL, $timestampParameterName);
    }
    public function timestampedController($controller, array $attributes=array(), array $query=array(), $updateTrackerName='global')
    {
        $query['timestamp'] = $this->updateManager->getLastUpdate($updateTrackerName);
        return new ControllerReference($controller, $attributes, $query);
    }
}

?>
