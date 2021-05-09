<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"listingId"})
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @Assert\NotBlank(message="Listing id is mandatory !"))
     * @ORM\Column(type="integer")
     */
    private $listingId;

    /**
     * @Assert\Length(max=255, maxMessage="No more than 255 characters !")
     * @ORM\Column(type="string", length=255)
     */
    private $artist;

    /**
     * @Assert\Length(max=255, maxMessage="No more than 255 characters !")
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Assert\Length(max=255, maxMessage="No more than 255 characters !")
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @Assert\Length(max=255, maxMessage="No more than 50 characters !")
     * @ORM\Column(type="string", length=50)
     */
    private $catno;

    /**
     * @Assert\Length(max=255, maxMessage="No more than 100 characters !")
     * @ORM\Column(type="string", length=100)
     */
    private $format;

    /**
     * @Assert\PositiveOrZero(message="Can't be negative !")
     * @ORM\Column(type="integer")
     */
    private $releaseId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @Assert\PositiveOrZero(message="Can't be negative !")
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $listed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $mediaCondition;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $sleeveCondition;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $acceptOffer;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $externalId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $formatQuantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $flatShipping;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListingId(): ?int
    {
        return $this->listingId;
    }

    public function setListingId(int $listingId): self
    {
        $this->listingId = $listingId;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCatno(): ?string
    {
        return $this->catno;
    }

    public function setCatno(string $catno): self
    {
        $this->catno = $catno;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getReleaseId(): ?int
    {
        return $this->releaseId;
    }

    public function setReleaseId(int $releaseId): self
    {
        $this->releaseId = $releaseId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getListed(): ?\DateTimeInterface
    {
        return $this->listed;
    }

    public function setListed(\DateTimeInterface $listed): self
    {
        $this->listed = $listed;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getMediaCondition(): ?string
    {
        return $this->mediaCondition;
    }

    public function setMediaCondition(string $mediaCondition): self
    {
        $this->mediaCondition = $mediaCondition;

        return $this;
    }

    public function getSleeveCondition(): ?string
    {
        return $this->sleeveCondition;
    }

    public function setSleeveCondition(?string $sleeveCondition): self
    {
        $this->sleeveCondition = $sleeveCondition;

        return $this;
    }

    public function getAcceptOffer(): ?bool
    {
        return $this->acceptOffer;
    }

    public function setAcceptOffer(?bool $acceptOffer): self
    {
        $this->acceptOffer = $acceptOffer;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getFormatQuantity(): ?int
    {
        return $this->formatQuantity;
    }

    public function setFormatQuantity(?int $formatQuantity): self
    {
        $this->formatQuantity = $formatQuantity;

        return $this;
    }

    public function getFlatShipping(): ?int
    {
        return $this->flatShipping;
    }

    public function setFlatShipping(?int $flatShipping): self
    {
        $this->flatShipping = $flatShipping;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }
}
