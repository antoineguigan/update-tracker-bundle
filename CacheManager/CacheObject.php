<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

/**
 * Used internally to store objects in the cache
 */
class CacheObject implements \Serializable
{
    private $object;
    private $timestamp;
    
    /**
     * Constructor
     * 
     * @param mixed $object the object that should be cached
     */
    public function __construct($object)
    {
        $this->object = $object;
        $this->timestamp = time();
    }
    
    /**
     * Returns the timestamp at which the object was cached
     * 
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Returns the stored object
     * 
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize(array($this->object, $this->timestamp));
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        list($this->object, $this->timestamp) = unserialize($serialized);
    }
}

?>
