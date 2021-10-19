<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Bundles\CoelacantheLabs\Annotation\Uploadable;
use Symfony\Component\String\Slugger\SluggerInterface;
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
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="books", cascade={"persist"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Borrow::class, inversedBy="books")
     */
    private $borrow;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, inversedBy="book", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cover;

    /**
     * @UploadableField(filename="cover", path="uploads")
     * @Assert\Image(
     *     maxHeight=500,
     *     maxWidth=500,
     *     maxHeightMessage="L'image doit avoir une hauteur maximale de 500 px (image carrée de préférence).",
     *     maxWidthMessage="L'image doit avoir une largeur maximale de 500 px (image carrée de préférence)."
     * )
     */
    private $coverFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $isReleasedAt;

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

    public function getCover(): ?Image
    {
        return $this->cover;
    }

    public function setCover(Image $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return mixed | void
     */
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
}
