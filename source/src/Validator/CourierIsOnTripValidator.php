<?php

declare(strict_types=1);

namespace App\Validator;

use App\DTO\TravelScheduleDTO;
use App\Entity\TravelSchedule;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CourierIsOnTripValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

        $result = $this->entityManager->getRepository(TravelSchedule::class)->createQueryBuilder('self')
            ->andWhere('self.dateDeparture >= :dateDeparture AND self.dateDeparture <= :dateArrival')
            ->orWhere('self.dateArrival >= :dateDeparture AND self.dateArrival <= :dateArrival')
            ->andWhere('self.courier = :courier')
            ->setParameters(
                [
                    'courier' => $value->getCourier(),
                    'dateDeparture' => $value->getDateDeparture(),
                    'dateArrival' => $dateArrival,
                ]
            )
            ->getQuery()
            ->getResult();

        if (0 === \count($result)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setTranslationDomain('messages')
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
