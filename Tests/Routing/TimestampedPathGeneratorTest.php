<?php

namespace Qimnet\UpdateTrackerBundle\Tests\Routing;

use Qimnet\UpdateTrackerBundle\Routing\TimestampedPathGenerator;

class TimestampedPathGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function getGenerateData() {
        return array(
            array('parameter'),
            array(false)
        );
    }

    /**
     * @dataProvider getGenerateData
     */
    public function testGenerate($parameterName)
    {
        $date = new \DateTime;
        $router = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $updateManger = $this->getMock('Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManagerInterface');
        $pathGenerator = new TimestampedPathGenerator($router, $updateManger, 'default_parameter');
        $updateManger->expects($this->once())
                ->method('getLastUpdate')
                ->with($this->equalTo('update_tracker_name'))
                ->will($this->returnValue($date));
        $router->expects($this->once())
                ->method('generate')
                ->with($this->equalTo('route'),
                        $this->equalTo(array(
                            'key1'=>'value1',
                            $parameterName?:'default_parameter'=>$date->format(('U'))
                        )),
                        $this->equalTo('reference_type'))
                ->will($this->returnValue('success'));
        $result = $pathGenerator->generate('route', array('key1'=>'value1'), 'update_tracker_name', 'reference_type', $parameterName);
        $this->assertEquals('success', $result);
    }
    
}

?>
