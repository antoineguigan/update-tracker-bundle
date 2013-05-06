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
 * This interface should be implemented on update tracker listeners
 */
interface UpdateListenerInterface
{
    /**
     * Called when the given update tracker is changed
     *
     * @param \Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerInterface $updateCalled
     */
    public function onUpdate(UpdateTrackerInterface $update);
}
