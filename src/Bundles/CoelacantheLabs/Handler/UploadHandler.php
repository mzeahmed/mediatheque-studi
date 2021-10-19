<?php

namespace App\Bundles\CoelacantheLabs\Handler;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UploadHandler
{
    private \Symfony\Component\PropertyAccess\PropertyAccessor $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Charge le fichier si le fichier est une instance de UploadedFile
     *
     * @param $entity
     * @param $property
     * @param $annotation
     */
    public function uploadFile($entity, $property, $annotation)
    {
        $file = $this->accessor->getValue($entity, $property);

        if ($file instanceof UploadedFile) {
            $this->removeOldFile($entity, $annotation);
            $filename = $file->getClientOriginalName();
            $file->move($annotation->getPath(), $filename);
            $this->accessor->setValue($entity, $annotation->getFilename(), $filename);
        }
    }

    /**
     * Supprime l'ancien fichier après ajout d'un nouveau
     *
     * @param $entity
     * @param $annotation
     */
    private function removeOldFile($entity, $annotation)
    {
        $file = $this->getFileFromFilename($entity, $annotation);

        if ($file !== null) {
            @unlink($file->getRealPath());
        }
    }

    /**
     * Recupere le fichier à partir du nom de fichier
     *
     * @param $entity
     * @param $annotation
     *
     * @return File|null
     */
    private function getFileFromFilename($entity, $annotation): ?File
    {
        $filename = $this->accessor->getValue($entity, $annotation->getFilename());

        if (empty($filename)) {
            return null;
        } else {
            return new File($annotation->getPath() . DIRECTORY_SEPARATOR . $filename, false);
        }
    }

    /**
     * Enregistre le nom du fichier à partir du nom original
     *
     * @param $entity
     * @param $property
     * @param $annotation
     */
    public function setFilenameFromFile($entity, $property, $annotation)
    {
        $file = $this->getFileFromFilename($entity, $annotation);
        $this->accessor->setValue($entity, $property, $file);
    }

    /**
     * Supprime le fichier
     *
     * @param $entity
     * @param $property
     */
    public function removeFile($entity, $property)
    {
        $file = $this->accessor->getValue($entity, $property);

        if ($file instanceof File) {
            @unlink($file->getRealPath());
        }
    }
}