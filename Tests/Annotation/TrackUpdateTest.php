<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\Tests\Annotation;

use Qimnet\UpdateTrackerTestBundle\Entity\TrackedEntityTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrackUpdateTest extends WebTestCase
{
    protected $container;
    protected $entityManager;
    protected function setUp()
    {
        parent::setUp();
        self::$kernel = static::createKernel();
        self::$kernel->boot();
        $this->container = self::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
    }

    public function testCreateEntity()
    {
        $e = new TrackedEntityTest;
        $e->setContent('test');
        $now = new \DateTime;
        $this->entityManager->persist($e);
        $this->entityManager->flush();
        $update = $this->container->get('qimnet.update_tracker.manager')->getLastUpdate('test');
        $this->assertInstanceOf('\DateTime',$update);
        $this->assertTrue($update >= $now);

        return $e;
    }
    /**
     * @depends testCreateEntity
     */
    public function testUpdateEntity(TrackedEntityTest $entity)
    {
        $this->entityManager->merge($entity);
        $tracker = $this->container->get('qimnet.update_tracker.manager');
        $lastDate = $tracker->getLastUpdate('test');

        $entity->setContent('test2');
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $update = $this->container->get('qimnet.update_tracker.manager')->getLastUpdate('test');
        $this->assertInstanceOf('\DateTime',$update);
        $this->assertTrue($update >= $lastDate);

        return $entity;
    }
    /**
     * @depends testUpdateEntity
     */
    public function testDeleteEntity(TrackedEntityTest $entity)
    {
        $this->entityManager->persist($entity);
        $tracker = $this->container->get('qimnet.update_tracker.manager');
        $lastDate = $tracker->getLastUpdate('test');

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $update = $this->container->get('qimnet.update_tracker.manager')->getLastUpdate('test');
        $this->assertInstanceOf('\DateTime',$update);
        $this->assertTrue($update >= $lastDate);

        return $entity;
    }
}
