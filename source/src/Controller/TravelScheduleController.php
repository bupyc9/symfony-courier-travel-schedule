<?php

namespace App\Controller;

use App\Entity\TravelSchedule;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TravelScheduleController extends AbstractController
{
    private const ITEMS_ON_PAGE = 10;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

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
     * @Route("/", name="travel_schedule")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $query = $this->getDoctrine()->getRepository(TravelSchedule::class)->createQueryBuilder('self')
            ->join('self.courier', 'courier')
            ->join('self.region', 'region')
            ->addSelect(['courier', 'region'])
            ->orderBy('self.dateDeparture', 'ASC')
            ->orderBy('self.dateArrival', 'ASC')
            ->getQuery()
        ;
        $page = $request->query->getInt('page', 1);
        $authors = $this->paginator->paginate($query, $page, self::ITEMS_ON_PAGE);

        return $this->render('travel_schedule/index.html.twig', ['items' => $authors]);
    }
}
