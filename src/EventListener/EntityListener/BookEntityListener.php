<?php

namespace App\EventListener\EntityListener;

use App\Entity\Book;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * On écoute le moment ou un book est créé ou modifié et initialiser automatiquement le slug
 */
class BookEntityListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Book $book, LifecycleEventArgs $event)
    {
        $book->initializeSlug($this->slugger);
        $book->setIsPublishedAt(new \DateTime());
    }

    public function preUpdate(Book $book, LifecycleEventArgs $event)
    {
        $book->initializeSlug($this->slugger);
        $book->setIsUpdatedAt(new \DateTime());
    }
}
