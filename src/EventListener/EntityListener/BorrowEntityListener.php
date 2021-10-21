<?php

namespace App\EventListener\EntityListener;

use App\Entity\Borrow;
use Doctrine\ORM\Event\LifecycleEventArgs;

class BorrowEntityListener
{
    public function prePersist(Borrow $borrow, LifecycleEventArgs $event)
    {
        $borrow->setIsCreatedAt(new \DateTime());
        $borrow->setBookReturnDate(new \DateTime('+ 21 days'));
    }
}
