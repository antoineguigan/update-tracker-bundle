<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
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
