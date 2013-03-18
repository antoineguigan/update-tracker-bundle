<?php
namespace Qimnet\UpdateTrackerBundle\Tests\CacheManager;

use Qimnet\UpdateTrackerBundle\CacheManager\CacheManager;

class CacheManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function getMockRepositories($repository='repository')
    {
        $repositories =  $this->getMock('\Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositoriesInterface');
        $repositories
                ->expects($this->once())
                ->method('getRepository')
                ->with($this->equalTo('repository'))
                ->will($this->returnValue($repository));
        return $repositories;
    }
    
    protected function getMockRepository()
    {
        return $this->getMock('\Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositoryInterface');
    }

    protected function getMockUpdateManager()
    {
        return $this->getMock('\Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManagerInterface');
    }
    public function testGetRepository()
    {
        $manager = new CacheManager($this->getMockUpdateManager(), $this->getMockRepositories());
        $this->assertEquals('repository', $manager->getRepository('repository'));
    }
    /**
     * @depends testGetRepository
     */
    public function testGetObject()
    {
        $date = new \DateTime;
        
        $repository = $this->getMockRepository();
        
        $repository
                ->expects($this->once())
                ->method('getObject')
                ->with( $this->equalTo('updateTracker'), 
                        $this->equalTo('key'), 
                        $this->equalTo('callback'), 
                        $this->equalTo('ttl'),
                        $this->equalTo($date->format('U')))
                ->will($this->returnValue('SUCCESS'));
        
        $updateManager = $this->getMockUpdateManager();
        $updateManager
                ->expects($this->once())
                ->method('getLastUpdate')
                ->with($this->equalTo('updateTracker'))
                ->will($this->returnValue($date));
                
        $cacheManager = new CacheManager($updateManager, $this->getMockRepositories($repository));
        
        $this->assertEquals('SUCCESS', $cacheManager->getObject('updateTracker', 'key', 'callback', 'ttl', 'repository'));
    }
    
    public function testRemoveObjects()
    {
        $repository = $this->getMockRepository();
        
        $repository
                ->expects($this->once())
                ->method('removeObjects')
                ->with($this->equalTo('namespaces'));
        
        $manager = new CacheManager($this->getMockUpdateManager(), $this->getMockRepositories($repository));
        
        $manager->removeObjects('namespaces', 'repository');
    }
}

?>
