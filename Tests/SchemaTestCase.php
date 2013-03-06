<?php
namespace Qimnet\UpdateTrackerBundle\Tests;
require_once dirname(__DIR__).'/../../../app/AppKernel.php';

use Doctrine\ORM\Tools\SchemaTool;

abstract class SchemaTestCase extends \PHPUnit_Framework_TestCase
{
    /**
    * @var Symfony\Component\HttpKernel\AppKernel
    */
    protected static $kernel;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected static $entityManager;
    
    /**
     * @var Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected static $doctrine;

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected static $container;


    public static function setUpBeforeClass()
    {
        // Boot the AppKernel in the test environment and with the debug.
        self::$kernel = new \AppKernel('test', true);
        self::$kernel->boot();

        // Store the container and the entity manager in test case properties
        self::$container = self::$kernel->getContainer();
        self::$doctrine = self::$container->get('doctrine');
        self::$entityManager = self::$doctrine->getEntityManager();

        // Build the schema for sqlite
        self::generateSchema();


        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass()
    {
        // Shutdown the kernel.
        self::$kernel->shutdown();

        parent::tearDownAfterClass();
    }

    protected static function generateSchema()
    {
        // Get the metadatas of the application to create the schema.
        $metadatas = self::getMetadatas();

        if ( ! empty($metadatas)) {
            // Create SchemaTool
            $tool = new SchemaTool(self::$entityManager);
            $tool->updateSchema($metadatas);
        } else {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Overwrite this method to get specific metadatas.
     *
     * @return Array
     */
    protected static function getMetadatas()
    {
        return self::$entityManager->getMetadataFactory()->getAllMetadata();
    }
}