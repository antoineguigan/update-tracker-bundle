<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

/**
 * APC implementation of the cache repository
 * 
 */
class APCCacheRepository extends AbstractCacheRepository
{
    /**
     * @inheritdoc
     */
    protected function loadObject($key)
    {
        return function_exists('apc_fetch') ? apc_fetch($key) : false;
    }

    /**
     * @inheritdoc
     */
    protected function removeObjectsByPrefix($prefix)
    {
        if (function_exists('apc_delete') && class_exists('\APCIterator')) {
            apc_delete(new \APCIterator('user', "@^$prefix@", APC_ITER_KEY));
        }
    }

    /**
     * @inheritdoc
     */
    protected function saveObject($key, $object, $ttl)
    {
        if (function_exists('apc_store')) {
            apc_store($key, $object, $ttl);
        }
    }
}

?>
