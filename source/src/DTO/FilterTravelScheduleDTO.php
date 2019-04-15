<?php

declare(strict_types=1);

namespace App\DTO;

use Carbon\Carbon;
use DateTimeInterface;

class FilterTravelScheduleDTO
{
    /**
     * @var DateTimeInterface
     */
    private $dateDeparture;

    /**
     * @var DateTimeInterface
     */
    private $dateArrival;

    public function __construct()
    {
        $this->dateDeparture = Carbon::now()->setTime(0, 0);
        $this->dateArrival = Carbon::now()->addDays(1)->setTime(0, 0);
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
