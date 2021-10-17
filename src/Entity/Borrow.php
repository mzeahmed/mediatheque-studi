<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="borrows")
     */
    private $borrower;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="borrow")
     */
    private $books;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBorrowed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $isBorrowedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $isReturnedAt;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setBorrow($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getBorrow() === $this) {
                $book->setBorrow(null);
            }
        }

        return $this;
    }

    public function getIsBorrowed(): ?bool
    {
        return $this->isBorrowed;
    }

    public function setIsBorrowed(bool $isBorrowed): self
    {
        $this->isBorrowed = $isBorrowed;

        return $this;
    }

    public function getIsBorrowedAt(): ?\DateTimeInterface
    {
        return $this->isBorrowedAt;
    }

    public function setIsBorrowedAt(\DateTimeInterface $isBorrowedAt): self
    {
        $this->isBorrowedAt = $isBorrowedAt;

        return $this;
    }

    public function getIsReturnedAt(): ?\DateTimeInterface
    {
        return $this->isReturnedAt;
    }

    public function setIsReturnedAt(\DateTimeInterface $isReturnedAt): self
    {
        $this->isReturnedAt = $isReturnedAt;

        return $this;
    }
}
