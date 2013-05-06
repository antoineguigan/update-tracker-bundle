<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\Response;

use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManagerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Generates public cached HTTP responses for a given update tracker namespace.
 *
 * The following cache properties are set by the factory:
 *   - public
 *   - maxAge
 *   - sharedMaxAge
 *   - lastModified
 */
class HTTPCachedResponseFactory
{
    protected $manager;
    protected $maxAge;
    protected $sharedMaxAge;

    /**
     * Constructor
     *
     * @param UpdateManagerInterface $manager
     * @param int                    $maxAge
     * @param int                    $sharedMaxAge
     */
    public function __construct(UpdateManagerInterface $manager, $maxAge=60, $sharedMaxAge=60)
    {
        $this->manager = $manager;
        $this->maxAge = $maxAge;
        $this->sharedMaxAge = $sharedMaxAge;
    }
    /**
     * Generate a Response object
     *
     * @param  mixed    $date     a single namespace, an array of namespaces, or a \DateTime object
     * @param  Response $response
     * @return Response
     */
    public function generate($date='global', Response $response=null)
    {
        if (!$response) $response = new Response;
        if ($this->maxAge) $response->setMaxAge ($this->maxAge);
        if ($this->sharedMaxAge) $response->setSharedMaxAge ($this->sharedMaxAge);
        if ($date instanceof \DateTime) {
            $response->setLastModified($date);
        } else {
            $response->setLastModified($this->manager->getLastUpdate($date));
        }

        return $response;
    }
}
