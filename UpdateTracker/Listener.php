<?php

namespace Qimnet\HTTPBundle\UpdateTracker;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\Common\Annotations\Reader as AnnotationReader;

class Listener implements EventSubscriber
{
    protected $annotationReader;
    protected $repository;


    public function __construct(AnnotationReader $annotationReader, Repository $repository)
    {
        $this->annotationReader = $annotationReader;
        $this->repository = $repository;
    }
    public function getSubscribedEvents()
    {
        return array('onFlush');
    }
    public function onFlush(OnFlushEventArgs $args)
    {
        $classes = array();
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $add_classes = function($entities) use (&$classes)
        {
            foreach($entities as $entity)
            {
                $class = get_class($entity);
                if (!in_array($class, $classes))
                {
                    $classes[] = $class;
                }
            }
        };
        $add_classes($uow->getScheduledEntityDeletions());
        $add_classes($uow->getScheduledEntityInsertions());
        $add_classes($uow->getScheduledEntityUpdates());
        $updates = array();
        foreach($classes as $class)
        {
            $annotation = $this->annotationReader->getClassAnnotation(new \ReflectionClass($class), 'Qimnet\HTTPBundle\Annotation\TrackUpdate');
            if ($annotation)
            {
                if (is_array($annotation->name))
                {
                    $updates = array_unique(array_merge($annotation->name, $updates));
                }
                elseif(!in_array($annotation->name, $updates))
                {
                    $updates[] = $annotation->name;
                }
            }
        }
        if (count($updates))
        {
            $repository = $this->repository->getEntityRepository($em);
            $meta = $em->getClassMetadata($repository->getClassName());
            foreach ($updates as $name)
            {
                $update = $this->repository->markUpdated($em, $name);
                $em->persist($update);
                $uow->computeChangeSet($meta, $update);
            }
        }
    }
}

?>
