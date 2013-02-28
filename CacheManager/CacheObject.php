<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

class CacheObject implements \Serializable
{
    private $object;
    private $timestamp;
    
    public function __construct($object)
    {
        $this->object = $object;
        $this->timestamp = time();
    }
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function serialize()
    {
        return serialize(array($this->object, $this->timestamp));
    }

    public function unserialize($serialized)
    {
        list($this->object, $this->timestamp) = unserialize($serialized);
    }
}

?>
