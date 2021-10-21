<?php

namespace App\Entity;

use Exception;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Bundles\CoelacantheLabs\Annotation\Uploadable;
use App\Bundles\CoelacantheLabs\Annotation\UploadableField;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @Uploadable()
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $isReleasedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="books", cascade={"persist"})
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string | null
     */
    private $coverName;

    /**
     * @UploadableField(filename="coverName", path="uploads/books")
     * @Assert\Image(
     *     maxWidth=800,
     *     maxWidthMessage="L'image doit avoir une largeur maximale de 800 px (image carrée de préférence)."
     * )
     * @Assert\NotBlank(message="Vous devez charger une image de couverture")
     *
     * @var File
     */
    private $coverFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $isPublishedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $isUpdatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReserved = 0;

    /**
     * @ORM\OneToMany(targetEntity=Borrow::class, mappedBy="book", orphanRemoval=true)
     */
    private $borrows;

    public function __construct()
    {
        $this->genre   = new ArrayCollection();
        $this->borrows = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    /**
     * @param SluggerInterface $slugger
     */
    public function initializeSlug(SluggerInterface $slugger)
    {
        $this->slug = $slugger->slug($this->getTitle())->lower();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genre $genre): self
    {
        if (! $this->genre->contains($genre)) {
            $this->genre[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genre->removeElement($genre);

        return $this;
    }

    public function getCoverName()
    {
        return $this->coverName;
    }

    /**
     * @param $coverName
     */
    public function setCoverName($coverName): void
    {
        $this->coverName = $coverName;
    }

    public function getCoverFile()
    {
        return $this->coverFile;
    }

    /**
     * @param $coverFile
     */
    public function setCoverFile($coverFile): void
    {
        $this->coverFile = $coverFile;
    }

    public function getIsReleasedAt(): ?\DateTimeInterface
    {
        return $this->isReleasedAt;
    }

    public function setIsReleasedAt(\DateTimeInterface $isReleasedAt): self
    {
        $this->isReleasedAt = $isReleasedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIsPublishedAt(): ?\DateTimeInterface
    {
        return $this->isPublishedAt;
    }

    public function setIsPublishedAt(?\DateTimeInterface $isPublishedAt): self
    {
        $this->isPublishedAt = $isPublishedAt;

        return $this;
    }

    public function getIsUpdatedAt(): ?\DateTimeInterface
    {
        return $this->isUpdatedAt;
    }

    public function setIsUpdatedAt(?\DateTimeInterface $isUpdatedAt): self
    {
        $this->isUpdatedAt = $isUpdatedAt;

        return $this;
    }

    public function getIsReserved(): ?bool
    {
        return $this->isReserved;
    }

    public function setIsReserved(bool $isReserved): self
    {
        $this->isReserved = $isReserved;

        return $this;
    }

    /**
     * @return Collection|Borrow[]
     */
    public function getBorrows(): Collection
    {
        return $this->borrows;
    }

    public function addBorrow(Borrow $borrow): self
    {
        if (! $this->borrows->contains($borrow)) {
            $this->borrows[] = $borrow;
            $borrow->setBook($this);
        }

        return $this;
    }

    public function removeBorrow(Borrow $borrow): self
    {
        if ($this->borrows->removeElement($borrow)) {
            // set the owning side to null (unless already changed)
            if ($borrow->getBook() === $this) {
                $borrow->setBook(null);
            }
        }

        return $this;
    }
}
