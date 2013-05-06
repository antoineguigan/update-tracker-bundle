<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Qimnet\UpdateTrackerBundle\UpdateTracker;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\Common\Annotations\Reader as AnnotationReader;

/**
 * Listens to Doctrine events, and updates the update tracker repository
 *
 * Entities can be trackeds using the TrackUpdateTest annotation, or TrackUpdateInterface
 */
class DoctrineEventSubscriber implements EventSubscriber
{
    protected $annotationReader;
    protected $repository;

    /**
     * Constructor
     *
     * @param AnnotationReader                 $annotationReader
     * @param UpdateTrackerRepositoryInterface $repository
     */
    public function __construct(AnnotationReader $annotationReader, UpdateTrackerRepositoryInterface $repository)
    {
        $this->annotationReader = $annotationReader;
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return array('onFlush');
    }
    public function onFlush(OnFlushEventArgs $args)
    {
        $classes = array();
        $updates = array();
        $addUpdate = function($name) use (&$updates) {
            if (is_array($name)) {
                 $updates = array_unique(array_merge($name, $updates));
             } elseif (!in_array($name, $updates)) {
                 $updates[] = $name;
             }
        };
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $add_classes = function($entities) use (&$classes, $addUpdate) {
            foreach ($entities as $entity) {
                if ($entity instanceof TrackUpdateInterface) {
                    $addUpdate($entity->getUpdateTrackerName());
                }
                $class = get_class($entity);
                if (!in_array($class, $classes)) {
                    $classes[] = $class;
                }
            }
        };
        $add_classes($uow->getScheduledEntityDeletions());
        $add_classes($uow->getScheduledEntityInsertions());
        $add_classes($uow->getScheduledEntityUpdates());
        foreach ($classes as $class) {
            $annotation = $this->annotationReader->getClassAnnotation(new \ReflectionClass($class), 'Qimnet\UpdateTrackerBundle\Annotation\TrackUpdate');
            if ($annotation) {
                $addUpdate($annotation->name);
            }
        }
        if (count($updates)) {
            $repository = $this->repository->getEntityRepository($em);
            $meta = $em->getClassMetadata($repository->getClassName());
            $repositoryUpdates = $this->repository->markUpdated($em, $updates);
            foreach ($repositoryUpdates as $update) {
                $em->persist($update);
                $uow->computeChangeSet($meta, $update);
            }
        }
    }

}
