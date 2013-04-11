<?php

namespace Qimnet\UpdateTrackerBundle\Routing;
use Symfony\Component\Routing\RouterInterface;

interface TimestampedPathGeneratorInterface
{
    public function generate($name, array $parameters=array(), $updateTrackerName='global', $referenceType = RouterInterface::ABSOLUTE_PATH, $timestampParameterName=null);
}

?>
