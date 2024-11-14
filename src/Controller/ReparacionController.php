<?php

namespace App\Controller;

use App\Repository\ReparacionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReparacionController extends AbstractController
{
    #[Route('/reparaciones', name: 'reparaciones', methods: ['GET'])]
    public function index(ReparacionRepository $reparacionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');
        $filter = $request->query->get('filter', '');

        $queryBuilder = $reparacionRepository->createQueryBuilder('r');

        if ($searchTerm) {
            $queryBuilder->where('r.descripcion LIKE :search')
                         ->setParameter('search', '%' . $searchTerm . '%');
        }

        switch ($filter) {
            case 'consolas':
                return $this->redirectToRoute('reparaciones_consolas', ['search' => $searchTerm]);
            case 'otros_dispositivos':
                return $this->redirectToRoute('reparaciones_otros_dispositivos', ['search' => $searchTerm]);
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('reparaciones.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/reparaciones/consolas', name: 'reparaciones_consolas', methods: ['GET'])]
    public function indexConsolas(ReparacionRepository $reparacionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');

        $queryBuilder = $reparacionRepository->createQueryBuilder('r')
            ->join('r.usuario', 'u')
            ->join('r.ticketReparacion', 't')
            ->join('t.producto', 'p')
            ->leftJoin('App\Entity\Consola', 'c', 'WITH', 'c.producto = p.id');

        if ($searchTerm) {
            $queryBuilder->where('u.nombre LIKE :search')
                         ->setParameter('search', '%' . $searchTerm . '%');
        }

        $queryBuilder->andWhere('c.id IS NOT NULL');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('reparaciones.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/reparaciones/otros_dispositivos', name: 'reparaciones_otros_dispositivos', methods: ['GET'])]
    public function indexOtrosDispositivos(ReparacionRepository $reparacionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');

        $queryBuilder = $reparacionRepository->createQueryBuilder('r')
            ->join('r.usuario', 'u')
            ->join('r.ticketReparacion', 't')
            ->join('t.producto', 'p')
            ->leftJoin('App\Entity\OtroDispositivo', 'od', 'WITH', 'od.producto = p.id');

        if ($searchTerm) {
            $queryBuilder->where('u.nombre LIKE :search')
                         ->setParameter('search', '%' . $searchTerm . '%');
        }

        $queryBuilder->andWhere('od.id IS NOT NULL');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('reparaciones.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}