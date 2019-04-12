<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TravelScheduleRepository")
 */
class TravelSchedule
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Courier", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $courier;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDeparture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateArrival;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCourier(): ?Courier
    {
        return $this->courier;
    }

    public function setCourier(Courier $courier): self
    {
        $this->courier = $courier;

        return $this;
    }

    public function getDateDeparture(): ?DateTimeInterface
    {
        return $this->dateDeparture;
    }

    public function setDateDeparture(DateTimeInterface $dateDeparture): self
    {
        $this->dateDeparture = $dateDeparture;

        return $this;
    }

    public function getDateArrival(): ?DateTimeInterface
    {
        return $this->dateArrival;
    }

    public function setDateArrival(DateTimeInterface $dateArrival): self
    {
        $this->dateArrival = $dateArrival;

        return $this;
    }
}
