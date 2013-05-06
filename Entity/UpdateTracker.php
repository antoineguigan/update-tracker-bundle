<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerInterface;

/**
 * Base UpdateTracker entity class
 *
 * This class should be subclassed as an entity in your project
 *
 * @ORM\MappedSuperclass
 */
abstract class UpdateTracker implements UpdateTrackerInterface
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column
     */
    protected $name;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @inheritdoc
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
