<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\FilterTravelScheduleDTO;
use App\DTO\TravelScheduleDTO;
use App\Entity\TravelSchedule;
use App\Form\FilterTravelScheduleType;
use App\Form\TravelScheduleType;
use App\Repository\TravelScheduleRepository;
use Carbon\Carbon;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TravelScheduleController extends AbstractController
{
    private const ITEMS_ON_PAGE = 10;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var TravelScheduleRepository
     */
    private $repository;

    /**
     * @param PaginatorInterface $paginator
     *
     * @return TravelScheduleController
     *
     * @required
     */
    public function setPaginator(PaginatorInterface $paginator): self
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * @param TravelScheduleRepository $repository
     *
     * @return TravelScheduleController
     *
     * @required
     */
    public function setRepository(TravelScheduleRepository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function index(Request $request): Response
    {
        $dto = new FilterTravelScheduleDTO();
        $form = $this->createForm(FilterTravelScheduleType::class, $dto);
        $form->handleRequest($request);

        $query = $this->repository->createQueryBuilder('self')
            ->andWhere('self.dateDeparture >= :dateDeparture')
            ->andWhere('self.dateArrival <= :dateArrival')
            ->setParameters(['dateDeparture' => $dto->getDateDeparture(), 'dateArrival' => $dto->getDateArrival()])
            ->join('self.courier', 'courier')
            ->join('self.region', 'region')
            ->addSelect(['courier', 'region'])
            ->orderBy('self.dateDeparture', 'ASC')
            ->getQuery()
        ;
        $page = $request->query->getInt('page', 1);
        $authors = $this->paginator->paginate($query, $page, self::ITEMS_ON_PAGE);

        return $this->render('travel_schedule/index.html.twig', ['items' => $authors, 'form' => $form->createView()]);
    }

    public function create(): Response
    {
        $form = $this->createForm(TravelScheduleType::class);

        return $this->render('travel_schedule/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $form = $this->createForm(TravelScheduleType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $data = [
                'success' => false,
                'errors' => $this->getFormErrors($form),
            ];

            return new JsonResponse($data);
        }

        /** @var TravelScheduleDTO $dto */
        $dto = $form->getData();

        $dateArrival = Carbon::now();
        $dateArrival->addDays($dto->getRegion()->getTravelTime());
        $dateArrival->setTime(0, 0);
        $travelSchedule = new TravelSchedule();
        $travelSchedule
            ->setRegion($dto->getRegion())
            ->setCourier($dto->getCourier())
            ->setDateDeparture($dto->getDateDeparture())
            ->setDateArrival($dateArrival)
        ;

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($travelSchedule);
        $entityManager->flush();

        $data = [
            'success' => true,
            'id' => $travelSchedule->getId(),
        ];

        return new JsonResponse($data);
    }

    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $name => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $name => $child) {
            if ($child->isSubmitted() && $child->isValid()) {
                continue;
            }

            $child->count() > 0 && $errors[$name] = $this->getFormErrors($child);

            foreach ($child->getErrors() as $error) {
                $errors[$name][] = $error->getMessage();
            }
        }

        return $errors;
    }
}
