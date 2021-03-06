<?php

declare(strict_types=1);

namespace App\Validator;

use App\DTO\TravelScheduleDTO;
use App\Repository\TravelScheduleRepository;
use Carbon\Carbon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CourierIsOnTripValidator extends ConstraintValidator
{
    /**
     * @var TravelScheduleRepository
     */
    private $repository;

    public function __construct(TravelScheduleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param TravelScheduleDTO          $value
     * @param Constraint|CourierIsOnTrip $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CourierIsOnTrip) {
            throw new UnexpectedTypeException($constraint, CourierIsOnTrip::class);
        }

        if (!\is_object($value) || null === $value->getRegion()) {
            return;
        }

        if (!$value instanceof TravelScheduleDTO) {
            throw new UnexpectedValueException($value, CourierIsOnTrip::class);
        }

        $dateArrival = Carbon::instance(clone $value->getDateDeparture());
        $dateArrival->addDays($value->getRegion()->getTravelTime())->setTime(0, 0);

        $result = $this->repository->findPeriodIntersections(
            $value->getCourier(),
            $value->getDateDeparture(),
            $dateArrival
        );

        if (0 === \count($result)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setTranslationDomain('messages')
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
