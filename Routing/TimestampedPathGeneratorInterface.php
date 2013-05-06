<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Qimnet\UpdateTrackerBundle\Routing;
use Symfony\Component\Routing\RouterInterface;

interface TimestampedPathGeneratorInterface
{
    public function generate($name, array $parameters=array(), $updateTrackerName='global', $referenceType = RouterInterface::ABSOLUTE_PATH, $timestampParameterName=null);
}
