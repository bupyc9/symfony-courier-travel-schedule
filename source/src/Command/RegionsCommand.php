<?php

namespace App\Command;

use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RegionsCommand extends Command
{
    protected static $defaultName = 'app:regions';
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
            ->setDescription('Add regions')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $items = $this->getItems();
        $repository = $this->entityManager->getRepository(Region::class);
        $count = 0;
        foreach ($items as $item) {
            $region = $repository->findOneBy(['title' => $item['title']]);
            if (null !== $region) {
                continue;
            }

            $region = new Region();
            $region->setTitle($item['title'])->setTravelTime($item['travelTime']);
            $this->entityManager->persist($region);
            ++$count;
        }

        $this->entityManager->flush();

        $io->success("Added {$count} regions");
    }

    protected function getItems(): array
    {
        return [
            ['title' => 'Санкт-Петербург', 'travelTime' => 1],
            ['title' => 'Уфа', 'travelTime' => 2],
            ['title' => 'Нижний Новгород', 'travelTime' => 3],
            ['title' => 'Владимир', 'travelTime' => 4],
            ['title' => 'Кострома', 'travelTime' => 5],
            ['title' => 'Екаетеренбург', 'travelTime' => 6],
            ['title' => 'Ковров', 'travelTime' => 7],
            ['title' => 'Воронеж', 'travelTime' => 8],
            ['title' => 'Самара', 'travelTime' => 9],
            ['title' => 'Астрахань', 'travelTime' => 10],
        ];
    }
}
