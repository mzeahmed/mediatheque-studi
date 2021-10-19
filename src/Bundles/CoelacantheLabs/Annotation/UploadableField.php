<?php

namespace App\Bundles\CoelacantheLabs\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class UploadableField
{
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var string
     */
    private string $path;

    public function __construct(array $options)
    {
        if (empty($options['filename'])) {
            throw new \InvalidArgumentException("L'annotation UploadableField doit avoir un attribut 'filename'");
        }

        if (empty($options['path'])) {
            throw new \InvalidArgumentException("L'annotation UplodableField doit avoir un attribut 'path'");
        }

        $this->filename = $options['filename'];
        $this->path     = $options['path'];
    }

    /**
     * @return string
     */
    public function getFilename(): mixed
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(mixed $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getPath(): mixed
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(mixed $path): void
    {
        $this->path = $path;
    }
}