<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

/**
 * Base implementation for cache repositories
 */
abstract class AbstractCacheRepository implements CacheRepositoryInterface
{
    protected $defaultTTL;
    protected $debug;
    protected $prefix;
    
    /**
     * 
     * @param string $prefix The prefix of the objects in the cache
     * @param type $defaultTTL The default TTL for cache objects
     * @param type $debug True to deactivate caching
     */
    public function __construct($prefix, $defaultTTL=90, $debug=false)
    {
        $this->defaultTTL = $defaultTTL;
        $this->debug = $debug;
        $this->prefix = $prefix;
    }

    /**
     * Returns the object as stored in the cache, or null
     * 
     * @param string $key The key of the object
     * @return mixed
     */
    abstract protected function loadObject($key);
    
    /**
     * Saves the object in the cache
     * 
     * @param string $key The key of the object
     * @param mixed $object The object to save
     * @param int $ttl The TTL of the object in the cache
     */
    abstract protected function saveObject($key, $object, $ttl);
    
    /**
     * Removes the cached objects for the given prefix
     * @param string $prefix 
     */
    abstract protected function removeObjectsByPrefix($prefix);

    /**
     * Returns the absolute key of an object in the cache
     * 
     * @param string $namespace
     * @param string $key
     * @return sttring
     */
    protected function getObjectKey($namespace, $key)
    {
        return "$this->prefix/$namespace/$key";
    }
    /**
     * @inheritdoc
     */
    final public function getObject($namespace, $key, $callback, $ttl=false, $minTimestamp=false)
    {
        if ($this->debug) {
            return call_user_func($callback);
        } else {
            $objectKey = $this->getObjectKey($namespace, $key);
            $object = $this->loadObject($objectKey);
            if ($object && $minTimestamp && ($object->getTimestamp() < $minTimestamp)) {
                $object = false;
            }
            if (!$object) {
                $object = new CacheObject(call_user_func($callback));
                $this->saveObject($objectKey, $object, $ttl ? $ttl : $this->defaultTTL);
            } 
            return $object->getObject();
        }
    }
    /**
     * @inheritdoc
     */
    public function removeObjects($namespaces)
    {
        if (!$this->debug) {
            $namespaces = (array) $namespaces;
            foreach ($namespaces as $namespace) {
                $this->removeObjectsByPrefix("$this->prefix/" . ($namespace ? "$namespace/" : ''));
            }
        }
    }
}

?>
