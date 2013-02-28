<?php
namespace Qimnet\UpdateTrackerBundle\CacheManager;

interface CacheRepositoryInterface
{
    public function getObject($namespace, $key, $callback, $ttl=false, $minTimestamp=false);
    public function removeObjects($namespaces);
}

?>
