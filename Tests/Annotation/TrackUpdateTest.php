<?php
namespace Qimnet\UpdateTrackerBundle\Tests\Annotation;

use Qimnet\UpdateTrackerBundle\Tests\SchemaTestCase;
use Qimnet\UpdateTrackerTestBundle\Entity\TrackedEntityTest;

class TrackUpdateTest extends SchemaTestCase
{
    public function testCreateEntity()
    {
        $e = new TrackedEntityTest;
        $e->setContent('test');
        $now = new \DateTime;
        self::$entityManager->persist($e);
        self::$entityManager->flush();
        $update = self::$container->get('qimnet.update_tracker.manager')->getLastUpdate('test');
        $this->assertInstanceOf('\DateTime',$update);
        $this->assertTrue($update >= $now);
        return $e;
    }
    /**
     * @depends testCreateEntity
     */
    public function testUpdateEntity(TrackedEntityTest $entity)
    {
        $tracker = self::$container->get('qimnet.update_tracker.manager');
        $lastDate = $tracker->getLastUpdate('test');
        
        $entity->setContent('test2');
        self::$entityManager->persist($entity);
        self::$entityManager->flush();
        
        $update = self::$container->get('qimnet.update_tracker.manager')->getLastUpdate('test');
        $this->assertInstanceOf('\DateTime',$update);
        $this->assertTrue($update >= $lastDate);
        
        return $entity;
    }
    /**
     * @depends testUpdateEntity
     */
    public function testDeleteEntity(TrackedEntityTest $entity)
    {
        $tracker = self::$container->get('qimnet.update_tracker.manager');
        $lastDate = $tracker->getLastUpdate('test');
        
        self::$entityManager->remove($entity);
        self::$entityManager->flush();
        
        $update = self::$container->get('qimnet.update_tracker.manager')->getLastUpdate('test');
        $this->assertInstanceOf('\DateTime',$update);
        $this->assertTrue($update >= $lastDate);
        
        return $entity;
    }
}

?>
