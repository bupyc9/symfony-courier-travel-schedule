<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Courier;
use App\Entity\Region;
use App\Entity\TravelSchedule;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FillTravelScheduleCommand extends Command
{
    protected static $defaultName = 'app:fill-travel-schedule';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fill travel schedule')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $regions = $this->entityManager->getRepository(Region::class)->findAll();
        $couriers = $this->entityManager->getRepository(Courier::class)->findAll();

        $now = Carbon::now();
        $count = 0;
        foreach ($couriers as $courier) {
            $dateDeparture = Carbon::createFromDate(2015, 6, 1)->setTime(0, 0);
            while (true) {
                if ($dateDeparture >= $now) {
                    break;
                }

                $region = $regions[\array_rand($regions, 1)];

                $dateArrival = clone $dateDeparture;
                $dateArrival->addDays($region->getTravelTime())->setTime(0, 0);

                $travelSchedule = new TravelSchedule();
                $travelSchedule
                    ->setRegion($region)
                    ->setCourier($courier)
                    ->setDateDeparture($dateDeparture)
                    ->setDateArrival($dateArrival)
                ;

                $this->entityManager->persist($travelSchedule);
                $this->entityManager->flush();
                $this->entityManager->detach($travelSchedule);

                $dateDeparture->addDays($region->getTravelTime() * 2 + 1);
                ++$count;
            }
        }

        $io->success("Added {$count} travel schedules");
    }
}
