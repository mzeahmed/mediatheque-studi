<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="Le titre de l'image doit faire au moins 10 caractères")
     */
    private ?string $caption;

    /**
     * @ORM\OneToOne(targetEntity=Book::class, mappedBy="cover", cascade={"persist", "remove"})
     */
    private $book;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(Book $book): self
    {
        // set the owning side of the relation if necessary
        if ($book->getCover() !== $this) {
            $book->setCover($this);
        }

        $this->book = $book;

        return $this;
    }
}
