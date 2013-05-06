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

class CacheRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddObject()
    {
        $repository = new StubCacheRepository('TestCache');
        $object = new \stdClass();
        $this->assertSame($object, $repository->getObject('TestCache', 'test1', function() use ($object) {
            return $object;
        }));
        $this->assertSame($object, $repository->getObject('TestCache', 'test1', function(){}));

        $minTimestamp = time() + 86400;
        $object2 = new \stdClass();
        $this->assertSame($object, $repository->getObject('TestCache', 'test1', function() use ($object) {
            return $object2;
        }));
        $this->assertSame($object, $repository->getObject('TestCache', 'test1', function() use ($object) {
            return $object2;
        }));
        $this->assertSame($object2, $repository->getObject('TestCache', 'test1', function() use ($object2) {
            return $object2;
        },false, $minTimestamp));
    }
    /**
     * @depends testAddObject
     */
    public function testRemoveObject()
    {
        $repository = new StubCacheRepository('TestCache');
        $repository->removeObjects('TestCache');
        $this->assertNull($repository->getObject('TestCache', 'test1', function() {}));
    }

    public function testDebug()
    {
        $repository = new StubCacheRepository('TestCache', 90, true);
        $object = new \stdClass();
        $this->assertSame($object, $repository->getObject('TestCache', 'test1', function() use ($object) {
            return $object;
        }));

        $object2 = new \stdClass();
        $this->assertSame($object2, $repository->getObject('TestCache', 'test1', function() use ($object2) {
            return $object2;
        }));
    }
}
