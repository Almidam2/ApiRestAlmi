<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Producto;
use App\Entity\Juego;
use App\Entity\Consola;
use App\Entity\OtroDispositivo;
use App\Entity\Venta;
use App\Repository\ProductoRepository;
use App\Entity\TicketCompra;
use App\Entity\TicketAlquiler;
use App\Entity\TicketReparacion;
use Knp\Component\Pager\PaginatorInterface;

class ProductoController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    
    #[Route('/productos', name: 'productos', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $filter = $request->query->get('filter', '');

        // Si el usuario tiene el rol de "técnico de consolas", lo redirigimos
        if ($user && in_array('ROLE_TECNICO_CONSOLAS', $user->getRoles())) {
            return $this->redirectToRoute('productos_consolas');
        }

        // Obtener el término de búsqueda (si lo hay)
        $searchTerm = $request->query->get('search', '');

        // Crear el query builder para obtener productos
        $queryBuilder = $this->entityManager
            ->getRepository(Producto::class)
            ->createQueryBuilder('p');

        if ($searchTerm) {
            $queryBuilder->where('p.nombre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        switch ($filter) {
            case 'consolas':
                return $this->redirectToRoute('productos_consolas', ['search' => $searchTerm]);
            case 'juegos':
                return $this->redirectToRoute('productos_juegos', ['search' => $searchTerm]);
            case 'otros_dispositivos':
                return $this->redirectToRoute('productos_otros_dispositivos', ['search' => $searchTerm]);
            }

        // Paginación
        $pagination = $paginator->paginate(
            $queryBuilder, // query builder
            $request->query->getInt('page', 1), // Número de página
            5 // Limitar a 5 productos por página
        );

        // Renderizar la plantilla Twig con la paginación de productos
        return $this->render('succes.html.twig', [
            'pagination' => $pagination
        ]);
    }

#[Route('/productos/consolas', name: 'productos_consolas', methods: ['GET'])]
    public function indexConsolas(Request $request, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('search', '');

        // Crear el QueryBuilder para productos que tienen una consola asociada
        $queryBuilder = $this->entityManager->getRepository(Producto::class)->createQueryBuilder('p')
            ->innerJoin('App\Entity\Consola', 'c', 'WITH', 'c.producto = p.id');

        if ($searchTerm) {
            $queryBuilder->andWhere('p.nombre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('succes.html.twig', [
            'pagination' => $pagination
        ]);
    }


    #[Route('/productos/juegos', name: 'productos_juegos', methods: ['GET'])]
    public function indexJuegos(Request $request, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('search', '');

        // Crear el QueryBuilder para productos que tienen un juego asociado
        $queryBuilder = $this->entityManager->getRepository(Producto::class)->createQueryBuilder('p')
            ->innerJoin('App\Entity\Juego', 'j', 'WITH', 'j.producto = p.id');

        if ($searchTerm) {
            $queryBuilder->andWhere('p.nombre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('succes.html.twig', [
            'pagination' => $pagination
        ]);
    }

    
    #[Route('/productos/otros_dispositivos', name: 'productos_otros_dispositivos', methods: ['GET'])]
public function indexOtrosDispositivos(Request $request, PaginatorInterface $paginator): Response
{
    $searchTerm = $request->query->get('search', '');

    // Crear el QueryBuilder para productos que tienen un dispositivo asociado
    $queryBuilder = $this->entityManager->getRepository(Producto::class)->createQueryBuilder('p')
        ->innerJoin('App\Entity\OtroDispositivo', 'd', 'WITH', 'd.producto = p.id');

    if ($searchTerm) {
        $queryBuilder->andWhere('p.nombre LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
    }

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        5
    );

    return $this->render('succes.html.twig', [
        'pagination' => $pagination
    ]);
}


#[Route('/producto/add', name: 'api_create_producto', methods: ['GET', 'POST'])]
public function add(Request $request): Response
{
    if ($request->isMethod('POST')) {
        $nombre = $request->request->get('nombre');
        $precio = $request->request->get('precio');
        $descripcion = $request->request->get('descripcion');
        $tipo = $request->request->get('tipo');
        $otroDispositivoTipo = $request->request->get('otro_dispositivo_tipo');

        /** @var UploadedFile $imagen */
        $imagen = $request->files->get('imagen');
        if ($imagen) {
            $imagenNombre = uniqid().'.'.$imagen->guessExtension();

            try {
                $imagen->move(
                    $this->getParameter('uploads'), // Asegúrate de definir este parámetro en tu configuración
                    $imagenNombre
                );
            } catch (FileException $e) {
                // Manejar la excepción si es necesario
                $this->addFlash('error', 'No se pudo subir la imagen.');
                return $this->redirectToRoute('api_create_producto');
            }
        } else {
            $imagenNombre = null;
        }

        $producto = new Producto();
        $producto->setNombre($nombre);
        $producto->setPrecio($precio);
        $producto->setDescripcion($descripcion);
        $producto->setImagen($imagenNombre);

        $this->entityManager->persist($producto);

        switch ($tipo) {
            case 'juego':
                $juego = new Juego();
                $juego->setProducto($producto);
                $this->entityManager->persist($juego);
                break;
            case 'consola':
                $consola = new Consola();
                $consola->setProducto($producto);
                $this->entityManager->persist($consola);
                break;
            case 'otro_dispositivo':
                $otroDispositivo = new OtroDispositivo();
                $otroDispositivo->setProducto($producto);
                $otroDispositivo->setTipo($otroDispositivoTipo);
                $this->entityManager->persist($otroDispositivo);
                break;
            default:
                $this->addFlash('error', 'Tipo de producto inválido.');
                return $this->redirectToRoute('api_create_producto');
        }

        $this->entityManager->flush();

        $this->addFlash('success', 'Producto añadido exitosamente.');
        return $this->redirectToRoute('productos');
    }

    return $this->render('anadir.html.twig');
}


    #[Route('/producto/edit/{id}', name: 'producto_edit', methods: ['GET', 'POST', 'PUT'])]
public function edit(Request $request, int $id): Response
{
    $producto = $this->entityManager->getRepository(Producto::class)->find($id);

    if (!$producto) {
        throw $this->createNotFoundException('El producto no existe');
    }

    if ($request->isMethod('POST')) {
        $producto->setNombre($request->request->get('nombre'));
        $producto->setPrecio($request->request->get('precio'));
        $producto->setDescripcion($request->request->get('descripcion'));

        /** @var UploadedFile $imagen */
        $imagen = $request->files->get('imagen');
        if ($imagen) {
            $imagenNombre = uniqid().'.'.$imagen->guessExtension();

            try {
                $imagen->move(
                    $this->getParameter('uploads'), // Asegúrate de definir este parámetro en tu configuración
                    $imagenNombre
                );
                $producto->setImagen($imagenNombre);
            } catch (FileException $e) {
                // Manejar la excepción si es necesario
            }
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('productos');
    }

    return $this->render('editar.html.twig', [
        'producto' => $producto
    ]);
}

#[Route('/producto/delete_tipo/{id}', name: 'producto_delete_tipo', methods: ['DELETE', 'GET'])]
public function deleteTipo(Request $request, int $id): Response
{
    $producto = $this->entityManager->getRepository(Producto::class)->find($id);

    if (!$producto) {
        throw $this->createNotFoundException('El producto no existe');
    }

    // 1. Eliminar entradas en la tabla `consola` relacionadas con el producto
    $consolas = $this->entityManager->getRepository(Consola::class)->findBy(['producto' => $producto]);
    foreach ($consolas as $consola) {
        $this->entityManager->remove($consola);
    }

    // 2. Eliminar entradas en la tabla `juego` relacionadas con el producto
    $juegos = $this->entityManager->getRepository(Juego::class)->findBy(['producto' => $producto]);
    foreach ($juegos as $juego) {
        $this->entityManager->remove($juego);
    }

    // 3. Eliminar entradas en la tabla `otro_dispositivo` relacionadas con el producto
    $otrosDispositivos = $this->entityManager->getRepository(OtroDispositivo::class)->findBy(['producto' => $producto]);
    foreach ($otrosDispositivos as $otroDispositivo) {
        $this->entityManager->remove($otroDispositivo);
    }

    // Guardar los cambios después de eliminar las relaciones de tipo
    $this->entityManager->flush();

    return $this->redirectToRoute('productos');
}



#[Route('/success', name: 'success_page')]
public function success(Request $request, PaginatorInterface $paginator): Response
{
    $queryBuilder = $this->entityManager->getRepository(Producto::class)->createQueryBuilder('p');

    $pagination = $paginator->paginate(
        $queryBuilder, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        5 /*limit per page*/
    );

    // Renderizar la plantilla Twig con los productos
    return $this->render('succes.html.twig', [
        'pagination' => $pagination
    ]);
}
}