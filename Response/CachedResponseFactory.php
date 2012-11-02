<?php
namespace Qimnet\HTTPBundle\Response;

use Qimnet\HTTPBundle\UpdateTracker\Manager;
use Symfony\Component\HttpFoundation\Response;

class CachedResponseFactory
{
    protected $manager;
    protected $maxAge;
    protected $sharedMaxAge;
    
    public function __construct(Manager $manager, $maxAge, $sharedMaxAge)
    {
        $this->manager = $manager;
        $this->maxAge = $maxAge;
        $this->sharedMaxAge = $sharedMaxAge;
    }
    /**
     * @param mixed $trackerDomains
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generate($date='global', Response $response=null)
    {
        if (!$response) $response = new Response;
        if ($this->maxAge) $response->setMaxAge ($this->maxAge);
        if ($this->sharedMaxAge) $response->setSharedMaxAge ($this->sharedMaxAge);
        if ($date instanceof \DateTime)
        {
            $response->setLastModified($date);
        }
        else
        {
            $response->setLastModified($this->manager->getLastUpdate($date));
        }
        
        return $response;
    }
}

?>
