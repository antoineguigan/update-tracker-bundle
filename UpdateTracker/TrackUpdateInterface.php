<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

/**
 * Implement this interface on an entity to track its updates
 */
interface TrackUpdateInterface
{
    /**
     * Returns an update tracker namespace or an array of update tracker namespaces
     *
     * @return mixed
     */
    public function getUpdateTrackerName();
}
