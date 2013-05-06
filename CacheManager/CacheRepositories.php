<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\CacheManager;

/**
 * Collection of defined cache repositories
 */
class CacheRepositories implements CacheRepositoriesInterface
{
    protected $repositories = array();
    protected $defaultRepositoryName;

    /**
     * Constructor
     *
     * @param type $defaultRepositoryName the name of the default repository
     */
    public function __construct($defaultRepositoryName)
    {
        $this->defaultRepositoryName = $defaultRepositoryName;
    }

    /**
     * @inheritdoc
     */
    public function addRepository($name, CacheRepositoryInterface $repository)
    {
        $this->repositories[$name] = $repository;
    }

    /**
     * @inheritdoc
     */
    public function getRepository($name=false)
    {
        return $this->repositories[$name ? $name : $this->defaultRepositoryName];
    }
}
