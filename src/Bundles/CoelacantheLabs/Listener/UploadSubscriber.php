<?php

namespace App\Bundles\CoelacantheLabs\Listener;

use App\Bundles\CoelacantheLabs\Annotation\UploadAnnotationReader;
use App\Bundles\CoelacantheLabs\Handler\UploadHandler;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;

class UploadSubscriber implements EventSubscriber
{

    private UploadAnnotationReader $reader;

    private UploadHandler $handler;

    public function __construct(UploadAnnotationReader $reader, UploadHandler $handler)
    {
        $this->reader  = $reader;
        $this->handler = $handler;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            'prePersist',
            'preUpdate',
            'postLoad',
            'postRemove',
        ];
    }

    /**
     * @param EventArgs $event
     */
    public function prePersist(EventArgs $event)
    {
        $this->preEvent($event);
    }

    private function preEvent(EventArgs $event)
    {
        $entity = $event->getEntity();

        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->uploadFile($entity, $property, $annotation);
        }
    }

    public function preUpdate(EventArgs $event)
    {
        $this->preEvent($event);
    }

    /**
     * @param EventArgs $event
     */
    public function postLoad(EventArgs $event)
    {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->setFilenameFromFile($entity, $property, $annotation);
            $this->handler->setFilenameFromFile($entity, $property, $annotation);
        }
    }

    /**
     * @param EventArgs $event
     *
     * @throws \Exception
     */
    public function postRemove(EventArgs $event)
    {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->removeFile($entity, $property);
        }
    }
}