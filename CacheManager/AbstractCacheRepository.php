<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

abstract class AbstractCacheRepository implements CacheRepositoryInterface
{
    protected $defaultTTL;
    protected $debug;
    protected $prefix;
    
    public function __construct($prefix, $defaultTTL=90, $debug=false)
    {
        $this->defaultTTL = $defaultTTL;
        $this->debug = $debug;
        $this->prefix = $prefix;
    }

    abstract protected function loadObject($key);
    
    abstract protected function saveObject($key, $object, $ttl);
    
    abstract protected function removeObjectsByPrefix($prefix);

    protected function getObjectKey($namespace, $key)
    {
        return "$this->prefix/$namespace/$key";
    }

    public function getObject($namespace, $key, $callback, $ttl=false, $minTimestamp=false)
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
