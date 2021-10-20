<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BorrowRepository::class)
 */
class Borrow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $isCreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="borrows")
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="borrows")
     */
    private $borrower;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        if (empty($this->isCreatedAt)) {
            $this->isCreatedAt = new \Datetime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsCreatedAt(): ?\DateTimeInterface
    {
        return $this->isCreatedAt;
    }

    public function setIsCreatedAt(\DateTimeInterface $isCreatedAt): self
    {
        $this->isCreatedAt = $isCreatedAt;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getBorrower(): ?User
    {
        return $this->borrower;
    }

    public function setBorrower(?User $borrower): self
    {
        $this->borrower = $borrower;

        return $this;
    }
}
