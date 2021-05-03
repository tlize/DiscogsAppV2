<?php

namespace App\Entity;

use App\Repository\BuyerCountryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BuyerCountryRepository::class)
 */
class BuyerCountry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $orderNum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shippingAddress;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $country;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }
}
