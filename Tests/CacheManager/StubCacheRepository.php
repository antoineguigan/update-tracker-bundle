<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\Tests\CacheManager;

use Qimnet\UpdateTrackerBundle\CacheManager\AbstractCacheRepository;

class StubCacheRepository extends AbstractCacheRepository
{
    public static $objects=array();

    protected function loadObject($key)
    {
        return isset(self::$objects[$key]) ? self::$objects[$key] : null;
    }

    protected function removeObjectsByPrefix($prefix)
    {
        foreach (array_keys(self::$objects) as $key) {
            if (substr($key,0,  strlen($prefix))==$prefix) {
                unset(self::$objects[$key]);
            }
        }
    }

    protected function saveObject($key, $object, $ttl)
    {
        self::$objects[$key] = $object;
    }
}
