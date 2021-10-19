<?php

namespace App\Entity;

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
     * @ORM\ManyToOne(targetEntity=Borrow::class, inversedBy="books")
     */
    private $borrow;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string | null
     */
    private $coverName;

    /**
     * @UploadableField(filename="coverName", path="uploads/books")
     * @Assert\Image(
     *     maxHeight=800,
     *     maxWidth=800,
     *     maxHeightMessage="L'image doit avoir une hauteur maximale de 500 px (image carrée de préférence).",
     *     maxWidthMessage="L'image doit avoir une largeur maximale de 500 px (image carrée de préférence)."
     * )
     * @Assert\NotBlank(message="Vous devez charger une image de couverture")
     *
     * @var File
     */
    private $coverFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVailable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->genre = new ArrayCollection();

        if (empty($this->updatedAt)) {
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @param SluggerInterface $slugger
     */
    public function initializeSlug(SluggerInterface $slugger)
    {
        $this->slug = $slugger->slug($this->getTitle())->lower();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBorrow(): ?Borrow
    {
        return $this->borrow;
    }

    public function setBorrow(?Borrow $borrow): self
    {
        $this->borrow = $borrow;

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

    public function getIsVailable(): ?bool
    {
        return $this->isVailable;
    }

    public function setIsVailable(bool $isVailable): self
    {
        $this->isVailable = $isVailable;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
