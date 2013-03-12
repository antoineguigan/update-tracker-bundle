<?php
namespace Qimnet\UpdateTrackerBundle\Tests\UpdateTracker;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateTrackerRepositoryTest  extends WebTestCase
{
    const CLASS_NAME='Qimnet\UpdateTrackerTestBundle\Entity\UpdateTrackerTest';
    const ENTITY_NAME='QimnetUpdateTrackerTestBundle:UpdateTrackerTest';
    
    static $updates=array();
    protected $entityManager;

    protected function setUp()
    {
        parent::setUp();
        self::$kernel = static::createKernel();
        self::$kernel->boot();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testConstructByEntityName()
    {
        return new UpdateTrackerRepository(self::ENTITY_NAME);;
    }
    public function testConstructByClassName()
    {
        return new UpdateTrackerRepository(self::CLASS_NAME);
    }

    /**
     * @depends testConstructByEntityName
     */
    public function testGetEntityName(UpdateTrackerRepository $repository)
    {
        $this->assertEquals(self::ENTITY_NAME, $repository->getEntityName());
    }
    /**
     * @depends testConstructByEntityName
     */
    public function testGetEntityRepository(UpdateTrackerRepository $repository)
    {
        $entityRepository = $repository->getEntityRepository($this->entityManager);
        $this->assertInstanceOf('Doctrine\ORM\EntityRepository', $entityRepository);
        $this->assertEquals(self::CLASS_NAME, $entityRepository->getClassName());
        return $repository;
    }
    /**
     * @depends testGetEntityRepository
     */
    public function testDefaultValueIsDate(UpdateTrackerRepository $repository)
    {
        $entityRepository = $repository->getEntityRepository($this->entityManager);
        foreach($entityRepository->findAll() as $entity)
        {
            $this->entityManager->remove($entity);
        }
        $this->entityManager->flush();
        $this->assertInstanceOf('\DateTime', $repository->getLastUpdate($this->entityManager));
    }

    public function getNamespaces()
    {
        return array(
            array('namespace1'), 
            array('namespace2'),
            array(array('namespace1', 'namespace2', 'namespace3')),
            array(array('test', 'global')),
            array(array('test42')),
            array(array('global')));
    }
    /**
     * @depends testDefaultValueIsDate
     * @dataProvider getNamespaces
     */
    public function testMarkUpdated($namespaces)
    {
        $repository = new UpdateTrackerRepository(self::ENTITY_NAME);
        $updates = $repository->markUpdated($this->entityManager, $namespaces);
        foreach($updates as $update)
        {
            self::$updates[$update->getName()] = $update;
            $this->entityManager->persist($update);
        }
        $this->entityManager->flush();
        return $repository;
    }
    /**
     * @depends testMarkUpdated
     */
    public function testGetLastUpdate()
    {
        $repository = new UpdateTrackerRepository(self::ENTITY_NAME);
        $globalDate = self::$updates['global']->getDate();
        foreach(self::$updates as $namespace=>$update)
        {
            $this->assertEquals($globalDate,$repository->getLastUpdate($this->entityManager, $namespace));
        }
        $this->assertEquals($globalDate,$repository->getLastUpdate($this->entityManager, 'bogus'));
        $this->assertNull($repository->getLastUpdate($this->entityManager, 'bogus',false));
    }
    /**
     * @depends testGetLastUpdate
     */
    public function testAddEventListener()
    {
        $repository = new UpdateTrackerRepository(self::ENTITY_NAME);
        $listener = $this->getMock('Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateListenerInterface');
        $listener->expects($this->once())
                ->method('onUpdate');
        $repository->addEventListener($listener);
        $repository->markUpdated($this->entityManager, 'updated');
        return $repository;
    }
}

?>
