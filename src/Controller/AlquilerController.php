<?php

namespace App\Controller;

use App\Repository\AlquilerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlquilerController extends AbstractController
{
    #[Route('/alquileres', name: 'alquileres')]
    public function index(AlquilerRepository $alquilerRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $alquilerRepository->createQueryBuilder('a')
            ->join('a.usuario', 'u');

        if ($search = $request->query->get('search')) {
            $queryBuilder->where('u.nombre LIKE :search')
                         ->setParameter('search', '%' . $search . '%');
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('alquiler.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/alquileres/otros_dispositivos', name: 'alquileres_otros_dispositivos')]
public function indexOtrosDispositivos(AlquilerRepository $alquilerRepository, PaginatorInterface $paginator, Request $request): Response
{
    // Crear el QueryBuilder para obtener los alquileres y relacionarlos con Usuario, TicketAlquiler, Producto y OtrosDispositivos
    $queryBuilder = $alquilerRepository->createQueryBuilder('a')
        ->join('a.usuario', 'u')         // Relacionamos con Usuario
        ->join('a.ticketAlquiler', 't')  // Relacionamos con TicketAlquiler
        ->join('t.producto', 'p')        // Relacionamos con Producto desde TicketAlquiler
        ->leftJoin('App\Entity\OtroDispositivo', 'od', 'WITH', 'od.producto = p.id'); // Relacionamos con OtroDispositivo

    // Si se pasa un término de búsqueda, lo aplicamos para filtrar los usuarios por nombre
    if ($search = $request->query->get('search')) {
        $queryBuilder->where('u.nombre LIKE :search')
                     ->setParameter('search', '%' . $search . '%');
    }

    // Filtramos solo los alquileres asociados con Otros Dispositivos
    $queryBuilder->andWhere('od.id IS NOT NULL'); // Aseguramos que el alquiler esté asociado a un "Otro Dispositivo"

    // Realizamos la paginación de los resultados
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),                      // Consulta de Doctrine
        $request->query->getInt('page', 1),             // Página actual (por defecto es la 1)
        10                                               // Número de resultados por página (en este caso, 10)
    );

    // Renderizamos la vista con los resultados paginados
    return $this->render('alquiler.html.twig', [
        'pagination' => $pagination,  // Pasamos la paginación a la vista
    ]);
}


#[Route('/alquileres/consolas', name: 'alquileres_consolas')]
public function indexConsolas(AlquilerRepository $alquilerRepository, PaginatorInterface $paginator, Request $request): Response
{
    // Crear el QueryBuilder para obtener los alquileres y relacionarlos con Usuario, TicketAlquiler, Producto y Consola
    $queryBuilder = $alquilerRepository->createQueryBuilder('a')
        ->join('a.usuario', 'u')         // Relacionamos con Usuario
        ->join('a.ticketAlquiler', 't')  // Relacionamos con TicketAlquiler
        ->join('t.producto', 'p')        // Relacionamos con Producto desde TicketAlquiler
        ->leftJoin('App\Entity\Consola', 'c', 'WITH', 'c.producto = p.id'); // Relacionamos con Consola

    // Si se pasa un término de búsqueda, lo aplicamos para filtrar los usuarios por nombre
    if ($search = $request->query->get('search')) {
        $queryBuilder->where('u.nombre LIKE :search')
                     ->setParameter('search', '%' . $search . '%');
    }

    // Filtramos solo los alquileres asociados con Consolas
    $queryBuilder->andWhere('c.id IS NOT NULL'); // Aseguramos que el alquiler esté asociado a una "Consola"

    // Realizamos la paginación de los resultados
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),                      // Consulta de Doctrine
        $request->query->getInt('page', 1),             // Página actual (por defecto es la 1)
        10                                               // Número de resultados por página (en este caso, 10)
    );

    // Renderizamos la vista con los resultados paginados
    return $this->render('alquiler.html.twig', [
        'pagination' => $pagination,  // Pasamos la paginación a la vista
    ]);
}

#[Route('/alquileres/juegos', name: 'alquileres_juegos')]
public function indexJuegos(AlquilerRepository $alquilerRepository, PaginatorInterface $paginator, Request $request): Response
{
    // Crear el QueryBuilder para obtener los alquileres y relacionarlos con Usuario, TicketAlquiler, Producto y Juego
    $queryBuilder = $alquilerRepository->createQueryBuilder('a')
        ->join('a.usuario', 'u')         // Relacionamos con Usuario
        ->join('a.ticketAlquiler', 't')  // Relacionamos con TicketAlquiler
        ->join('t.producto', 'p')        // Relacionamos con Producto desde TicketAlquiler
        ->leftJoin('App\Entity\Juego', 'j', 'WITH', 'j.producto = p.id'); // Relacionamos con Juego

    // Si se pasa un término de búsqueda, lo aplicamos para filtrar los usuarios por nombre
    if ($search = $request->query->get('search')) {
        $queryBuilder->where('u.nombre LIKE :search')
                     ->setParameter('search', '%' . $search . '%');
    }

    // Filtramos solo los alquileres asociados con Juegos
    $queryBuilder->andWhere('j.id IS NOT NULL'); // Aseguramos que el alquiler esté asociado a un "Juego"

    // Realizamos la paginación de los resultados
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),                      // Consulta de Doctrine
        $request->query->getInt('page', 1),             // Página actual (por defecto es la 1)
        10                                               // Número de resultados por página (en este caso, 10)
    );

    // Renderizamos la vista con los resultados paginados
    return $this->render('alquiler.html.twig', [
        'pagination' => $pagination,  // Pasamos la paginación a la vista
    ]);
}

}