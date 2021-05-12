<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $buyer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $orderNum;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderTotal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orderFee;

    /**
     * @ORM\Column(type="integer")
     */
    private $itemId;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $itemPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $itemFee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $releaseId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $mediaCondition;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $sleeveCondition;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $externalId;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $itemRemoved;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shipping;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $invoice;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ratingOfBuyer;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ratingOfSeller;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ratingOfBuyerDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ratingOfSellerDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentAboutBuyer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentAboutSeller;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $archived;

    /**
     * @ORM\Column(type="text")
     */
    private $shippingAddress;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $buyerExtra;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastActivity;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $fromOffer;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    private $offerOriginalPrice;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $shippingMethod;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $location;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderItems")
     */
    private $order;


    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuyer(): ?string
    {
        return $this->buyer;
    }

    public function setBuyer(string $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getOrderNum(): ?string
    {
        return $this->orderNum;
    }

    public function setOrderNum(string $orderNum): self
    {
        $this->orderNum = $orderNum;

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

    public function getOrderTotal(): ?int
    {
        return $this->orderTotal;
    }

    public function setOrderTotal(int $orderTotal): self
    {
        $this->orderTotal = $orderTotal;

        return $this;
    }

    public function getOrderFee(): ?int
    {
        return $this->orderFee;
    }

    public function setOrderFee(?int $orderFee): self
    {
        $this->orderFee = $orderFee;

        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(int $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    public function getItemPrice(): ?string
    {
        return $this->itemPrice;
    }

    public function setItemPrice(string $itemPrice): self
    {
        $this->itemPrice = $itemPrice;

        return $this;
    }

    public function getItemFee(): ?int
    {
        return $this->itemFee;
    }

    public function setItemFee(int $itemFee): self
    {
        $this->itemFee = $itemFee;

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

    public function getReleaseId(): ?int
    {
        return $this->releaseId;
    }

    public function setReleaseId(int $releaseId): self
    {
        $this->releaseId = $releaseId;

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

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

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

    public function getItemRemoved(): ?string
    {
        return $this->itemRemoved;
    }

    public function setItemRemoved(?string $itemRemoved): self
    {
        $this->itemRemoved = $itemRemoved;

        return $this;
    }

    public function getShipping(): ?int
    {
        return $this->shipping;
    }

    public function setShipping(?int $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    public function setInvoice(?string $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getRatingOfBuyer(): ?string
    {
        return $this->ratingOfBuyer;
    }

    public function setRatingOfBuyer(?string $ratingOfBuyer): self
    {
        $this->ratingOfBuyer = $ratingOfBuyer;

        return $this;
    }

    public function getRatingOfSeller(): ?string
    {
        return $this->ratingOfSeller;
    }

    public function setRatingOfSeller(?string $ratingOfSeller): self
    {
        $this->ratingOfSeller = $ratingOfSeller;

        return $this;
    }

    public function getRatingOfBuyerDate(): ?\DateTimeInterface
    {
        return $this->ratingOfBuyerDate;
    }

    public function setRatingOfBuyerDate(?\DateTimeInterface $ratingOfBuyerDate): self
    {
        $this->ratingOfBuyerDate = $ratingOfBuyerDate;

        return $this;
    }

    public function getRatingOfSellerDate(): ?\DateTimeInterface
    {
        return $this->ratingOfSellerDate;
    }

    public function setRatingOfSellerDate(?\DateTimeInterface $ratingOfSellerDate): self
    {
        $this->ratingOfSellerDate = $ratingOfSellerDate;

        return $this;
    }

    public function getCommentAboutBuyer(): ?string
    {
        return $this->commentAboutBuyer;
    }

    public function setCommentAboutBuyer(?string $commentAboutBuyer): self
    {
        $this->commentAboutBuyer = $commentAboutBuyer;

        return $this;
    }

    public function getCommentAboutSeller(): ?string
    {
        return $this->commentAboutSeller;
    }

    public function setCommentAboutSeller(?string $commentAboutSeller): self
    {
        $this->commentAboutSeller = $commentAboutSeller;

        return $this;
    }

    public function getArchived(): ?string
    {
        return $this->archived;
    }

    public function setArchived(string $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getBuyerExtra(): ?string
    {
        return $this->buyerExtra;
    }

    public function setBuyerExtra(?string $buyerExtra): self
    {
        $this->buyerExtra = $buyerExtra;

        return $this;
    }

    public function getLastActivity(): ?\DateTimeInterface
    {
        return $this->lastActivity;
    }

    public function setLastActivity(?\DateTimeInterface $lastActivity): self
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getFromOffer(): ?string
    {
        return $this->fromOffer;
    }

    public function setFromOffer(?string $fromOffer): self
    {
        $this->fromOffer = $fromOffer;

        return $this;
    }

    public function getOfferOriginalPrice(): ?string
    {
        return $this->offerOriginalPrice;
    }

    public function setOfferOriginalPrice(?string $offerOriginalPrice): self
    {
        $this->offerOriginalPrice = $offerOriginalPrice;

        return $this;
    }

    public function getShippingMethod(): ?string
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod(string $shippingMethod): self
    {
        $this->shippingMethod = $shippingMethod;

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
