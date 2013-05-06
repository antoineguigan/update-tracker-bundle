<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Qimnet\UpdateTrackerBundle\Tests\UpdateTracker;

use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateManager;

class UpdateManagerTest extends \PHPUnit_Framework_TestCase
{
    const CLASS_NAME='Qimnet\UpdateTrackerTestBundle\Entity\UpdateTrackerTest';
    const ENTITY_NAME='QimnetUpdateTrackerTestBundle:UpdateTrackerTest';

    protected function getDoctrineMock()
    {
        $doctrine = $this->getMockBuilder('Doctrine\Bundle\DoctrineBundle\Registry')
                    ->disableOriginalConstructor()
                    ->getMock();
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                    ->disableOriginalConstructor()
                    ->getMock();
        $doctrine->expects($this->any())
                ->method('getManagerForClass')
                ->will($this->returnValue($em));

        return $doctrine;
    }
    protected function getRepositoryMock()
    {
        return $this->getMockBuilder('Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerRepository')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    public function testConstruct()
    {
        return new UpdateManager($this->getDoctrineMock(), $this->getRepositoryMock());
    }

    public function testMarkUpdated()
    {
        $repository = $this->getRepositoryMock();

        $updates = new \ArrayObject(array(new \stdClass));

        $repository->expects($this->atLeastOnce())
                ->method('markUpdated')
                ->will($this->returnValue($updates));

        $manager = new UpdateManager($this->getDoctrineMock(), $repository);

        $this->assertEquals($updates,$manager->markUpdated('global'));
        $this->assertEquals($updates,$manager->markUpdated('test'));
        $this->assertEquals($updates,$manager->markUpdated(array('test','test2')));

        return $manager;
    }
    public function testGetEntityName()
    {
        $repository = $this->getRepositoryMock();

        $repository->expects($this->once())
                ->method('getEntityName')
                ->will($this->returnValue(self::ENTITY_NAME));

        $manager = new UpdateManager($this->getDoctrineMock(), $repository);
        $this->assertEquals(self::ENTITY_NAME, $manager->getEntityName());
    }
    public function testGetEntityRepository()
    {
        $entityRepository = new \stdClass;
        $repository = $this->getRepositoryMock();

        $repository->expects($this->once())
                ->method('getEntityRepository')
                ->will($this->returnValue($entityRepository));

        $manager = new UpdateManager($this->getDoctrineMock(), $repository);
        $this->assertEquals($entityRepository, $manager->getEntityRepository());
    }
    public function testGetLastUpdate()
    {
        $update = new \stdClass();
        $repository = $this->getRepositoryMock();

        $repository->expects($this->atLeastOnce())
                ->method('getLastUpdate')
                ->will($this->returnValue($update));

        $manager = new UpdateManager($this->getDoctrineMock(), $repository);
        $this->assertEquals($update, $manager->getLastUpdate());
        $this->assertEquals($update, $manager->getLastUpdate('test'));
        $this->assertEquals($update, $manager->getLastUpdate(array('global','test')));
    }
}
