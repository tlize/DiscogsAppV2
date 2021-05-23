<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
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
     * @Assert\Positive(message="Order # can't be negative...")
     * @ORM\Column(type="string", length=20)
     */
    private $orderNum;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $shipping;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $fee;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tax;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $taxedAmount;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $taxJurisdiction;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $taxResponsibleParty;

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
     * @Assert\Regex(pattern="/[\r\n.*]/", message="Can't be just one line...")
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
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $offerOriginalPrice;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $shippingMethod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order", cascade="remove")
     */
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="integer")
     */
    private$nbItems;

    /**
     * @return mixed
     */
    public function getNbItems()
    {
        return $this->nbItems;
    }

    /**
     * @param mixed $nbItems
     */
    public function setNbItems($nbItems): void
    {
        $this->nbItems = $nbItems;
    }



    /**
     * @return mixed
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param mixed $orderItems
     */
    public function setOrderItems($orderItems): void
    {
        $this->orderItems = $orderItems;
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

    public function getOrderNum(): ?string
    {
        return $this->orderNum;
    }

    public function setOrderNum(string $orderNum): self
    {
        $this->orderNum = $orderNum;

        return $this;
    }

    public function getOrderDate(): ?DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getShipping(): ?string
    {
        return $this->shipping;
    }

    public function setShipping(?string $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(string $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function getTax(): ?string
    {
        return $this->tax;
    }

    public function setTax(?string $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getTaxedAmount(): ?string
    {
        return $this->taxedAmount;
    }

    public function setTaxedAmount(?string $taxedAmount): self
    {
        $this->taxedAmount = $taxedAmount;

        return $this;
    }

    public function getTaxJurisdiction(): ?string
    {
        return $this->taxJurisdiction;
    }

    public function setTaxJurisdiction(?string $taxJurisdiction): self
    {
        $this->taxJurisdiction = $taxJurisdiction;

        return $this;
    }

    public function getTaxResponsibleParty(): ?string
    {
        return $this->taxResponsibleParty;
    }

    public function setTaxResponsibleParty(?string $taxResponsibleParty): self
    {
        $this->taxResponsibleParty = $taxResponsibleParty;

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

    public function getRatingOfBuyerDate(): ?DateTimeInterface
    {
        return $this->ratingOfBuyerDate;
    }

    public function setRatingOfBuyerDate(?DateTimeInterface $ratingOfBuyerDate): self
    {
        $this->ratingOfBuyerDate = $ratingOfBuyerDate;

        return $this;
    }

    public function getRatingOfSellerDate(): ?DateTimeInterface
    {
        return $this->ratingOfSellerDate;
    }

    public function setRatingOfSellerDate(?DateTimeInterface $ratingOfSellerDate): self
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

    public function setArchived(?string $archived): self
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

    public function getLastActivity(): ?DateTimeInterface
    {
        return $this->lastActivity;
    }

    public function setLastActivity(?DateTimeInterface $lastActivity): self
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

    public function setFromOffer(string $fromOffer): self
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

    public function setShippingMethod(?string $shippingMethod): self
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $Country): self
    {
        $this->country = $Country;

        return $this;
    }
}
