<?php

namespace Qimnet\UpdateTrackerBundle\UpdateTracker;

interface UpdateTrackerInterface
{
    public function getName();
    public function setName($name);
    public function getDate();
    public function setDate(\DateTime $date);
}

?>
