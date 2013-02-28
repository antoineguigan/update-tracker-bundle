<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

class APCCacheRepository extends AbstractCacheRepository
{
    protected function loadObject($key)
    {
        return function_exists('apc_fetch') ? apc_fetch($key) : false;
    }

    protected function removeObjectsByPrefix($prefix)
    {
        if (function_exists('apc_delete') && class_exists('\APCIterator')) {
            apc_delete(new \APCIterator('user', "@^$prefix@", APC_ITER_VALUE));
        }
    }

    protected function saveObject($key, $object, $ttl)
    {
        if (function_exists('apc_store')) {
            apc_store($key, $object, $ttl);
        }
    }
}

?>
