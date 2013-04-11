<?php

namespace Qimnet\UpdateTrackerBundle\Tests\Templating;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//TODO test rendering with parameters

class CacheFragmentRendererFunctionalTest extends WebTestCase
{
    public function getRenderData()
    {
        return array(
            array('test'),
            array('test2'),
            array('test')
        );
    }

    /**
     * @dataProvider getRenderData
     */
    public function testRender($value)
    {
        $client = static::createClient();
        $client->request('GET', "/cache-fragment-test/$value");
        $this->assertEquals($value, $client->getResponse()->getContent());
    }
}

?>
