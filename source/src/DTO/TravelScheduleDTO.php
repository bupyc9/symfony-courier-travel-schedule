<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Courier;
use App\Entity\Region;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TravelScheduleDTO
{
    /**
     * @var Courier
     *
     * @Assert\NotBlank()
     */
    private $courier;

    /**
     * @var Region
     *
     * @Assert\NotBlank()
     */
    private $region;

    /**
     * @var DateTimeInterface
     *
     * @Assert\NotBlank()
     */
    private $dateDeparture;

    public function getCourier(): ?Courier
    {
        return $this->courier;
    }

    public function setCourier(Courier $courier): self
    {
        $this->courier = $courier;

        return $this;
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

    public function getDateDeparture(): ?DateTimeInterface
    {
        return $this->dateDeparture;
    }

    public function setDateDeparture(DateTimeInterface $dateDeparture): self
    {
        $this->dateDeparture = $dateDeparture;

        return $this;
    }
}
