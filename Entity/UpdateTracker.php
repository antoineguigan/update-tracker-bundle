<?php
namespace Qimnet\UpdateTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerInterface;

/**
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

    public function getDate()
    {
        return $this->date;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

?>
