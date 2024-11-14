<?php

namespace App\Controller;

use App\Repository\VentaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VentasController extends AbstractController
{
    #[Route('/ventas', name: 'ventas', methods: ['GET'])]
    public function index(VentaRepository $ventaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');
        $filter = $request->query->get('filter', '');

        $queryBuilder = $ventaRepository->createQueryBuilder('v')
            ->join('v.usuario', 'u');

        if ($searchTerm) {
            $queryBuilder->where('u.nombre LIKE :search')
                         ->setParameter('search', '%' . $searchTerm . '%');
        }

        switch ($filter) {
            case 'consolas':
                return $this->redirectToRoute('ventas_consolas', ['search' => $searchTerm]);
            case 'juegos':
                return $this->redirectToRoute('ventas_juegos', ['search' => $searchTerm]);
            case 'otros_dispositivos':
                return $this->redirectToRoute('ventas_otros_dispositivos', ['search' => $searchTerm]);
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ventas.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/ventas/consolas', name: 'ventas_consolas', methods: ['GET'])]
    public function indexConsolas(VentaRepository $ventaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');

        $queryBuilder = $ventaRepository->createQueryBuilder('v')
            ->join('v.ticketCompras', 'tc')
            ->join('tc.producto', 'p')
            ->leftJoin('App\Entity\Consola', 'c', 'WITH', 'c.producto = p.id');

        if ($searchTerm) {
            $queryBuilder->where('v.usuario LIKE :search')
                         ->setParameter('search', '%' . $searchTerm . '%');
        }

        $queryBuilder->andWhere('c.id IS NOT NULL');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ventas.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/ventas/juegos', name: 'ventas_juegos', methods: ['GET'])]
    public function indexJuegos(VentaRepository $ventaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');

        $queryBuilder = $ventaRepository->createQueryBuilder('v')
            ->join('v.ticketCompras', 'tc')
            ->join('tc.producto', 'p')
            ->leftJoin('App\Entity\Juego', 'j', 'WITH', 'j.producto = p.id');

        if ($searchTerm) {
            $queryBuilder->where('v.usuario LIKE :search')
                         ->setParameter('search', '%' . $searchTerm . '%');
        }

        $queryBuilder->andWhere('j.id IS NOT NULL');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ventas.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/ventas/otros_dispositivos', name: 'ventas_otros_dispositivos', methods: ['GET'])]
    public function indexOtrosDispositivos(VentaRepository $ventaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('search', '');

        $queryBuilder = $ventaRepository->createQueryBuilder('v')
            ->join('v.ticketCompras', 'tc')
            ->join('tc.producto', 'p')
            ->leftJoin('App\Entity\OtroDispositivo', 'od', 'WITH', 'od.producto = p.id');

        if ($searchTerm) {
            $queryBuilder->where('v.usuario LIKE :search')
                         ->setParameter('search', '%' . $searchTerm . '%');
        }

        $queryBuilder->andWhere('od.id IS NOT NULL');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ventas.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}