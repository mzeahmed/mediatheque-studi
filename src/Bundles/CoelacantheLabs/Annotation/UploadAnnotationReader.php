<?php

namespace App\Bundles\CoelacantheLabs\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;

class UploadAnnotationReader
{
    /**
     * @var AnnotationReader
     */
    private AnnotationReader $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param $entity
     *
     * @return array
     */
    public function getUploadableFields($entity): array
    {
        $reflection = new \ReflectionClass(get_class($entity));

        if (! $this->isUploadable($entity)) {
            return [];
        }

        $properties = [];

        foreach ($reflection->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, UploadableField::class);
            if ($annotation !== null) {
                $properties[$property->getName()] = $annotation;
            }
        }

        return $properties;
    }

    /**
     * @param $entity
     *
     * @return bool
     */
    public function isUploadable($entity): bool
    {
        $reflexion = new \ReflectionClass(get_class($entity));

        return $this->reader->getClassAnnotation($reflexion, Uploadable::class) !== null;
    }
}