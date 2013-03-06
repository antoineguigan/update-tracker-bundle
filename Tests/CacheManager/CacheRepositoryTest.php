<?php
namespace Qimnet\UpdateTrackerBundle\Tests\CacheManager;

use Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositoryInterface;

class CacheRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        return new StubCacheRepository('TestCache');
    }
    /**
     * @depends testConstruct
     */
    public function testAddObject(CacheRepositoryInterface $repository)
    {
        $object = new \stdClass();
        $this->assertEquals($object, $repository->getObject('TestCache', 'test1', function() use($object){
            return $object;
        }));
        $this->assertEquals($object, $repository->getObject('TestCache', 'test1', function(){}));
        
        $minTimestamp = time() + 86400;
        $object2 = new \stdClass();
        $this->assertEquals($object, $repository->getObject('TestCache', 'test1', function() use($object){
            return $object2;
        }));
        $this->assertEquals($object2, $repository->getObject('TestCache', 'test1', function() use($object){
            return $object2;
        },$minTimestamp));
        return $repository;
    }
    /**
     * @depends testAddObject
     */
    public function testRemoveObject(CacheRepositoryInterface $repository)
    {
        $repository->removeObjects('TestCache');
        $this->assertNull($repository->getObject('TestCache', 'test1', function() {}));
    }
}

?>
