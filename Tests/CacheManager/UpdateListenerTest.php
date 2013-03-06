<?php
namespace Qimnet\UpdateTrackerBundle\Tests\CacheManager;

use Qimnet\UpdateTrackerBundle\CacheManager\UpdateListener;

class UpdateListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testContruct()
    {
        $repositories = $this->getMockBuilder('Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositories')
                ->disableOriginalConstructor()
                ->getMock();
        return new UpdateListener($repositories);
    }
    public function testOnUpdate() {
        $repository = $this->getMock('Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositoryInterface');
        
        $repository->expects($this->once())
                ->method('removeObjects');
        
        $repositories = $this->getMockBuilder('Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositories')
                ->disableOriginalConstructor()
                ->getMock();
        $repositories->expects($this->once())
                ->method('getRepository')
                ->will($this->returnValue($repository));
        
        $update = $this->getMock('Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerInterface');

        $listener = new UpdateListener($repositories);
        $this->assertNull($listener->onUpdate($update));
    }
}

?>
