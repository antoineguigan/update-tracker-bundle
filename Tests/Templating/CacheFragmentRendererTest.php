<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\Tests\Templating;

use Qimnet\UpdateTrackerBundle\Templating\CacheFragmentRenderer;

//TODO test rendering with parameters

class CacheFragmentRendererTest extends \PHPUnit_Framework_TestCase
{
    public function getRenderOptions()
    {
        return array(
            array(array(), 'global'),
            array(array('updateTrackerName'=>'test'),'test'),
            array(array('ttl'=>90, 'repositoryName'=>'rep'),'global')
        );
    }

    /**
     * @dataProvider getRenderOptions
     */
    public function testRender($options, $updateTrackerName)
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $inlineRendererOptions = $options;
        if (!isset($inlineRendererOptions['updateTrackerName'])) {
            $inlineRendererOptions['updateTrackerName'] = 'global';
        }

        $inlineRenderer = $this->getMock('Symfony\Component\HttpKernel\Fragment\FragmentRendererInterface');

        $inlineRenderer
                ->expects($this->atLeastOnce())
                ->method('render')
                ->with( $this->anything(),
                        $this->equalTo($request),
                        $this->equalTo($inlineRendererOptions))
                ->will($this->returnValue('SUCCESS'));

        $cacheManager = $this->getMock('Qimnet\UpdateTrackerBundle\CacheManager\CacheManagerInterface');
        $cacheManager
                ->expects($this->once())
                ->method('getObject')
                ->with( $this->equalTo($updateTrackerName),
                        $this->stringStartsWith('controller/'),
                        $this->callback(function($closure){
                            $val = $closure();

                            return is_null($val) || ('SUCCESS' == $val);
                        }),
                        $this->equalTo(isset($options['ttl']) ? $options['ttl'] : null),
                        $this->equalTo(isset($options['repositoryName']) ? $options['repositoryName'] : null))
                ->will($this->returnValue('SUCCESS'));

        $renderer = new CacheFragmentRenderer($inlineRenderer, $cacheManager);
        $this->assertEquals('SUCCESS', $renderer->render('uri', $request, $options));
    }

}
