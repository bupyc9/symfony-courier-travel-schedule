<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Courier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CouriersCommand extends Command
{
    protected static $defaultName = 'app:couriers';

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
            ->setDescription('Add couriers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $items = $this->getItems();
        $repository = $this->entityManager->getRepository(Courier::class);
        $count = 0;
        foreach ($items as $item) {
            $courier = $repository->findOneBy(
                [
                    'firstName' => $item['firstName'],
                    'lastName' => $item['lastName'],
                    'secondName' => $item['secondName'],
                ]
            );
            if (null !== $courier) {
                continue;
            }

            $courier = new Courier();
            $courier
                ->setFirstName($item['firstName'])
                ->setLastName($item['lastName'])
                ->setSecondName($item['secondName']);
            $this->entityManager->persist($courier);
            ++$count;
        }

        $this->entityManager->flush();

        $io->success("Added {$count} couriers");
    }

    protected function getItems(): array
    {
        return [
            ['firstName' => 'Прохор', 'lastName' => 'Тимофеев', 'secondName' => 'Германович'],
            ['firstName' => 'Юстиниан', 'lastName' => 'Белов', 'secondName' => 'Гордеевич'],
            ['firstName' => 'Лукьян', 'lastName' => 'Филатов', 'secondName' => 'Александрович'],
            ['firstName' => 'Эрнест', 'lastName' => 'Фёдоров', 'secondName' => 'Антонинович'],
            ['firstName' => 'Анатолий', 'lastName' => 'Рогов', 'secondName' => 'Яковович'],
            ['firstName' => 'Варлаам', 'lastName' => 'Петухов', 'secondName' => 'Германович'],
            ['firstName' => 'Аполлон', 'lastName' => 'Капустин', 'secondName' => 'Ярославович'],
            ['firstName' => 'Захар', 'lastName' => 'Ершов', 'secondName' => 'Антонинович'],
            ['firstName' => 'Лукьян', 'lastName' => 'Гаврилов', 'secondName' => 'Арсеньевич'],
            ['firstName' => 'Юрий', 'lastName' => 'Поляков', 'secondName' => 'Леонидович'],
        ];
    }
}
