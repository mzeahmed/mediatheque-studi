<?php

namespace App\EventListener\EntityListener;

use App\Entity\Book;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

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
