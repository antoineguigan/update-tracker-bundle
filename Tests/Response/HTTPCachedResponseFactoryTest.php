<?php
namespace Qimnet\UpdateTrackerBundle\Tests\Response;

use Qimnet\UpdateTrackerBundle\Response\HTTPCachedResponseFactory;

class HTTPCachedResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $manager = $this->getMockBuilder('Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManager')
                ->disableOriginalConstructor()
                ->getMock();
        $manager->expects($this->any())
                ->method('getLastUpdate')
                ->will($this->returnValue(new \DateTime));
        return new HTTPCachedResponseFactory($manager);
    }
    /**
     * @depends testConstruct
     */
    public function testGenerate(HTTPCachedResponseFactory $factory)
    {
        $response = $factory->generate();
        
        $response2 = $factory->generate('test');
        
        $response3 = $factory->generate(new \DateTime);
        
        $response4 = $factory->generate('test', $response3);
        $this->assertSame($response3, $response4);
        $this->assertNotSame($response2, $response4);
    }
}

?>
