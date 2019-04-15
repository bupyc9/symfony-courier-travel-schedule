<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\TravelScheduleDTO;
use App\Entity\Courier;
use App\Entity\Region;
use App\Validator\CourierIsOnTrip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class TravelScheduleType extends AbstractType
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $couriers = $this->entityManager->getRepository(Courier::class)->findAll();
        $regions = $this->entityManager->getRepository(Region::class)->findAll();

        $builder
            ->setAction($this->router->generate('travel_schedule_store'))
            ->add('courier', EntityType::class, [
                'label' => 'travel_schedule.courier',
                'class' => Courier::class,
                'choices' => $couriers,
            ])
            ->add('region', EntityType::class, [
                'label' => 'travel_schedule.region',
                'class' => Region::class,
                'attr' => ['class' => 'region-js'],
                'choices' => $regions,
                'choice_attr' => static function (Region $region): array {
                    return ['data-travel-time' => $region->getTravelTime()];
                },
            ])
            ->add('dateDeparture', DateType::class, [
                'label' => 'travel_schedule.dateDeparture',
                'attr' => ['class' => 'date-departure-js'],
                'widget' => 'single_text',
            ])
            ->add('dateArrival', TextType::class, [
                'label' => 'travel_schedule.dateArrival',
                'attr' => ['readonly' => 'readonly', 'class' => 'date-arrival-js'],
                'mapped' => false,
            ])
            ->add('add', SubmitType::class, ['label' => 'base.add'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TravelScheduleDTO::class,
            'constraints' => [new CourierIsOnTrip()],
        ]);
    }
}
