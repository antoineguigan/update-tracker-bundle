<?php
namespace Qimnet\UpdateTrackerBundle\Tests\CacheManager;

use Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositories;

class CacheRepositoriesTest extends \PHPUnit_Framework_TestCase
{
    const DEFAULT_REPOSITORY_NAME='test';
    
    public function testConstruct() 
    {
        return new CacheRepositories(self::DEFAULT_REPOSITORY_NAME);
    }
    /**
     * @depends testConstruct
     */
    public function testAddRepository(CacheRepositories $repositories)
    {
        $repository = $this->getMock('Qimnet\UpdateTrackerBundle\CacheManager\CacheRepositoryInterface');
        $repositories->addRepository(self::DEFAULT_REPOSITORY_NAME, $repository);
        return array('repositories'=>$repositories, 'repository'=>$repository);
    }
    /**
     * @depends testAddRepository
     */
    public function testGetRepository(array $params)
    {
        extract($params);
        $this->assertEquals($repository, $repositories->getRepository());
        $this->assertEquals($repository, $repositories->getRepository(self::DEFAULT_REPOSITORY_NAME));
    }
}

?>
