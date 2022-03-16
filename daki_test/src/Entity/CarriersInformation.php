<?php

namespace App\Entity;

use App\Repository\CarriersInformationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarriersInformationRepository::class)
 */
class CarriersInformation
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
    private $carrierName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="integer")
     */
    private $weightMin;

    /**
     * @ORM\Column(type="integer")
     */
    private $weightMax;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    public function setCarrierName(string $carrierName): self
    {
        $this->carrierName = $carrierName;

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

    public function getWeightMin(): ?int
    {
        return $this->weightMin;
    }

    public function setWeightMin(int $weightMin): self
    {
        $this->weightMin = $weightMin;

        return $this;
    }

    public function getWeightMax(): ?int
    {
        return $this->weightMax;
    }

    public function setWeightMax(int $weightMax): self
    {
        $this->weightMax = $weightMax;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
