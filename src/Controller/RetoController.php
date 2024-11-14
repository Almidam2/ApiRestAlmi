<?php

namespace App\Controller;

use App\Entity\Alquiler;
use App\Entity\Consola;
use App\Entity\Juego;
use App\Entity\OtroDispositivo;
use App\Entity\Producto;
use App\Entity\Reparacion;
use App\Entity\TicketAlquiler;
use App\Entity\TicketCompra;
use App\Entity\TicketReparacion;
use App\Entity\Usuario;
use App\Entity\Venta;
use App\Repository\AlquilerRepository;
use App\Repository\ConsolaRepository;
use App\Repository\JuegoRepository;
use App\Repository\OtroDispositivoRepository;
use App\Repository\ProductoRepository;
use App\Repository\ReparacionRepository;
use App\Repository\TicketAlquilerRepository;
use App\Repository\TicketCompraRepository;
use App\Repository\TicketReparacionRepository;
use App\Repository\UsuarioRepository;
use App\Repository\VentaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PSpell\Config;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\Cloner\Data;

class RetoController extends AbstractController
{
    #[Route('/ws/reto', name: 'app_reto')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RetoController.php',
        ]);
    }
    

    #[Route('/ws/loginTecnicos', name: 'app_tecnico')]
    public function indexx()
    {
        //$tecnico = $usuarioRepository->findBy(['tipo' => 'tecnico']);

        return $this->render('base.html.twig');
    }
     

    #[Route('/ws/producto/upload', name: 'upload_img_producto', methods: ['POST'])]
    public function upload(ProductoRepository $pr,Request $request): Response
    {
       // Configura la ruta donde se guardarán las imágenes
    $destination = $this->getParameter('kernel.project_dir').'/uploads';

    // Obtiene el ID del usuario y el archivo de la solicitud
    $producto_id = $request->request->get('producto_id');
    $file = $request->files->get('file');

    // Verifica si se ha subido un archivo
    if (!$file) {
        return $this->json(['message' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
    }

    // Valida el tipo de archivo (puedes ajustar los tipos permitidos)
   /* $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
        return $this->json(['message' => 'Invalid file type'], Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }*/
        //dd();
    // Verifica si el directorio de destino existe, si no, lo crea
    if (!is_dir($destination)) {
        if (!mkdir($destination, 0755, true)) {
            return $this->json(['message' => 'Unable to create the upload directory'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Genera un nuevo nombre de archivo único
    $newFilename = uniqid() . '.' . $file->getClientOriginalExtension();

    // Mueve el archivo a la carpeta de destino
    try {
        $file->move($destination, $newFilename);
    } catch (\Exception $e) {
        return $this->json(['message' => 'An error occurred while uploading the file: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Busca el usuario por su ID
    $producto = $pr->find($producto_id);
    if (!$producto) {
        return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }

    // Actualiza el nombre de la imagen en el usuario
    $producto->setImagen($newFilename);
   $pr->add($producto); // Guarda los cambios en la base de datos

    // Devuelve una respuesta JSON con la información del archivo subido
    return $this->json(['message' => 'File uploaded successfully', 'filename' => $newFilename, 'path' => $destination . '/' . $newFilename]);
    }

    #[Route('/ws/juego', name: 'app_juego', methods: ['GET'])]
        public function gameIndex(JuegoRepository $pr): JsonResponse
    {
        return  $this->converToJson($pr->getProduct());
    }

    #[Route('/ws/juego/{nombre}', name: 'gameByname', methods: ['GET'])]
    public function gameByName(JuegoRepository $jr,ProductoRepository $pr,$nombre): JsonResponse
    {
        $productos=$jr->getProductbyName($nombre);
        return  $this->converToJson($productos);
    }

    #[Route('/ws/consola', name: 'app_consola', methods: ['GET'])]
    public function consoleIndex(ConsolaRepository $ur): JsonResponse
    {
        return  $this->converToJson($ur->getProduct());
    }
    #[Route('/ws/movil', name: 'app_movil', methods: ['GET'])]
    public function movilIndex(OtroDispositivoRepository $pr): JsonResponse
    {
        return  $this->converToJson($pr->getProduct());
    }

    #[Route('/ws/user', name: 'add-usuario', methods: ['POST'])]
    public function addUser(UsuarioRepository $ur,Request $request): JsonResponse
    {
        $data=Json_decode($request->getContent(), true);
        //dd($data);
        if(empty($data)){

            throw new NotfoundHttpException('Faltan parametros');
        }
        //dd($data);
        $usuario=new Usuario($data['correo'],$data['nombre'],$data['apellido'],$data['username'],$data['contrasena']);
        $ur->add($usuario);
        return $this->converToJson($data);
    }

    #[Route('/ws/user/alt', name: 'alt-usuario', methods: ['POST'])]
    public function altUser(UsuarioRepository $ur,Request $request): JsonResponse
    {
        $data=Json_decode($request->getContent(), true);
        if(empty($data)){

            throw new NotfoundHttpException('Faltan parametros');
        }
        $user=$ur->findOneBy(['id'=>$data['id']]);
        //dd($data);
        $user->setNombre($data['nombre']);
        $user->setApellido($data['apellido']);
        $user->setCorreo($data['correo']);
        $ur->add($user);
        return $this->converToJson($data);
    }

    #[Route('/ws/user/{username}', name: 'get-usuario', methods: ['GET'])]
    public function getUserByUsername(UsuarioRepository $ur,$username): JsonResponse
    {
        return $this->converToJson($ur->findOneBy(['username'=>$username]));
    }

    #[Route('/ws/users', name: 'get_usuarios', methods: ['GET'])]
    public function getAllUsers(UsuarioRepository $ur): JsonResponse
    {
        // Obtener todos los usuarios
        $usuarios = $ur->findAll();
    

        // Retornar la respuesta en formato JSON
        return $this->converToJson($ur->findAll());
    }




    #[Route('/ws/producto/getPic/{id}', name: 'photo_producto', methods: ['GET'])]
    public function getPhoto(ProductoRepository $ur,$id): Response
    {


        $producto = $ur->findOneBy(['id' => $id]);
        //dd($usuario);
        // Definir la ubicación de los archivos
        $path =  $this->getParameter('kernel.project_dir').'/uploads/'. $producto->getImagen();

        // Verificar si el archivo existe
        if (!file_exists($path)) {
            return $this->json(['message' => 'File not found   ' . $path], Response::HTTP_NOT_FOUND);
        }

        $filename = $producto->getImagen();
        $path = $this->getParameter('kernel.project_dir') . '/uploads/' . $filename;
        return new Response(file_get_contents($path), 200, [
            'Content-Type' => 'image/' . pathinfo($filename, PATHINFO_EXTENSION),
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
        ]);
    }

        #[Route('/ws/user/upload', name: 'uploade_usuario_pic', methods: ['POST'])]
        public function uploadPicUser(UsuarioRepository $ur,Request $request): Response{
          
            $destination = $this->getParameter('kernel.project_dir').'/uploads';

    // Obtiene el ID del usuario y el archivo de la solicitud
    $userid = $request->request->get('usuario_id');
    $file = $request->files->get('file');
    $userid = intval($userid);

    // Verifica si se ha subido un archivo
    if (!$file) {
        return $this->json(['message' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
    }

    // Valida el tipo de archivo (puedes ajustar los tipos permitidos)
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
        return $this->json(['message' => 'Invalid file type'], Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }
        //dd();
    // Verifica si el directorio de destino existe, si no, lo crea
    if (!is_dir($destination)) {
        if (!mkdir($destination, 0755, true)) {
            return $this->json(['message' => 'Unable to create the upload directory'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Genera un nuevo nombre de archivo único
    $newFilename = uniqid() . '.' . $file->getClientOriginalExtension();

    // Mueve el archivo a la carpeta de destino
    try {
        $file->move($destination, $newFilename);
    } catch (\Exception $e) {
        return $this->json(['message' => 'An error occurred while uploading the file: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Busca el usuario por su ID
    $usuario = $ur->findOneBy(['id'=>$userid]);
    if (!$usuario) {
        return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }

    // Actualiza el nombre de la imagen en el usuario
    $usuario->setImagen($newFilename);
   $ur->add($usuario); // Guarda los cambios en la base de datos
//dd($newFilename);
    // Devuelve una respuesta JSON con la información del archivo subido
    return $this->json(['message' => 'File uploaded successfully', 'filename' => $newFilename, 'path' => $destination . '/' . $newFilename]);
        }

    #[Route('/ws/user/getPic/{id}', name: 'user_getpic', methods: ['GET'])]
    public function getUserPhoto(UsuarioRepository $ur,$id): Response
    {


        $usuario = $ur->findOneBy(['id' => $id]);
        //dd($usuario);
        // Definir la ubicación de los archivos
        $path = $this->getParameter('kernel.project_dir') . '/uploads/' . $usuario->getImagen();

        // Verificar si el archivo existe
        if (!file_exists($path)) {
            return $this->json(['message' => 'File not found   ' . $path], Response::HTTP_NOT_FOUND);
        }

        $filename = $usuario->getImagen();
        $path = $this->getParameter('kernel.project_dir') . '/uploads/' . $filename;
        return new Response(file_get_contents($path), 200, [
            'Content-Type' => 'image/' . pathinfo($filename, PATHINFO_EXTENSION),
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
        ]);
    }


    #[Route('/ws/producto/subir', name: 'upload_productos', methods: ['POST'])]
public function subirProducto(Request $request, ProductoRepository $pr): Response
{
    // Obtener datos del formulario
    $data=Json_decode($request->getContent(), true);
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $descripcion = $data['descripcion'];
    $tipoProducto = $request->request->get('tipo'); 

    // Crear nuevo producto
    $producto = new Producto();
    $producto->setNombre($nombre);
    $producto->setPrecio((int)$precio);
    $producto->setDescripcion($descripcion);


    // Respuesta de éxito
            $pr->add($producto);
    return $this->json(['message' => 'Producto subido correctamente', 'producto' => $producto->getId()], Response::HTTP_CREATED);
}


    #[Route('/ws/user/del/{id}', name: 'user', methods: ['DELETE'])]
    public function DeleteUser(UsuarioRepository $ur,$id): Response
    {


        $usuario = $ur->findOneBy(['id' => $id]);

        $ur->remove($usuario);
        return  $this->converToJson($usuario);

    }

    #[Route('/ws/venta/add', name: 'add_venta', methods: ['POST'])]
    public function addVenta(VentaRepository $vr,UsuarioRepository $ur,Request $request): JsonResponse
    {  
        $data=json_decode($request->getContent(),true);
        $fecha = \DateTime::createFromFormat('d/m/Y', $data['fecha']);
        $precio=$data['precio'];
        //dd($data['fecha']);
        if ($fecha === false) {
            return $this->json(['error' => 'Fecha inválida. El formato debe ser dd/MM/yyyy'], 400);
        }


        //dd($fecha);
        $usuario=$ur->findOneBy(['id'=>$data['usuario']]);

        if (!$usuario) {
            return $this->json(['error' => 'Usuario no encontrado'], 404);
        }
        $venta = new Venta($usuario, $fecha,$precio);
        $vr->add($venta);

        //$fechaString = $fecha->format('Y-m-d H:i:s');
        return $this->json(['id'=>$venta->getId()]);
    }

    #[Route('/ws/venta', name: 'get_ventasByUser', methods: ['GET'])]
    public function getVentaByUserId(VentaRepository $pr,Request $request): JsonResponse
    {
        $data=json_decode($request->getContent(),true);
        // Obtener todos los productos
        $userid=$data['id'];
        $ventas = $pr->findOneBy(['usuario'=>$userid], ['fecha' => 'DESC']);
        
    
        // Retornar la respuesta en formato JSON
        return $this->converToJson($ventas);
    }

    #[Route('/ws/ticketVenta', name: 'add_ticketVenta', methods: ['POST'])]
    public function addTicketCompra(TicketCompraRepository $tr,VentaRepository $vr,ProductoRepository $pr,Request $request): JsonResponse
    {
        $data=json_decode($request->getContent(), true);
        $idProduto=$data['producto'];
        $idVenta=$data['venta'];
        $producto=$pr->findOneBy(['id'=>$idProduto]);
        $venta=$vr->findOneBy(['id'=>$idVenta]);
        $ticket=new TicketCompra($venta,$producto);
        $tr->add($ticket);
    
    
        // Retornar la respuesta en formato JSON
        return $this->json(['message' => 'ticket creado']);
    }
    
#[Route('/ws/alq/add', name: 'add_Alq', methods: ['POST'])]
public function addAlq(AlquilerRepository $ar, UsuarioRepository $ur, ProductoRepository $pr, Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $fecha_i = \DateTime::createFromFormat('d/m/Y', $data['fecha_inicio']);
    $fecha_f = \DateTime::createFromFormat('d/m/Y', $data['fecha_fin']);

    if ($fecha_i === false) {
        return $this->json(['error' => 'Fecha inicio inválida. El formato debe ser dd/MM/yyyy'], 400);
    }
    if ($fecha_f === false) {
        return $this->json(['error' => 'Fecha fin inválida. El formato debe ser dd/MM/yyyy'], 400);
    }

    $usuario = $ur->findOneBy(['id' => $data['usuario']]);
    if (!$usuario) {
        return $this->json(['error' => 'Usuario no encontrado'], 404);
    }

    /*$producto = $pr->findOneBy(['id' => $data['producto']]);
    if (!$producto) {
        return $this->json(['error' => 'Producto no encontrado'], 404);
    }*/

    $precio = $data['precio'];
    $alquiler = new Alquiler($usuario, $fecha_i, $fecha_f, $precio);
    $ar->add($alquiler);

    return $this->json(['id' => $alquiler->getId()]);
}

    #[Route('/ws/ticketAlq', name: 'add_ticketAlq', methods: ['POST'])]
    public function addTicketAlq(TicketAlquilerRepository $tar,AlquilerRepository $ar,ProductoRepository $pr,Request $request): JsonResponse
    {
        $data=json_decode($request->getContent(), true);
        $idProduto=$data['producto'];
        $idAlquiler=$data['alquiler'];
        $producto=$pr->findOneBy(['id'=>$idProduto]);
        $alquiler=$ar->findOneBy(['id'=>$idAlquiler]);
        $ticket=new TicketAlquiler($alquiler,$producto);
        $tar->add($ticket);
    
    
        // Retornar la respuesta en formato JSON
        return $this->json(['message' => 'ticket creado']);
    }


    #[Route('/ws/rep/add', name: 'add_Rep', methods: ['POST'])]
    public function addRep(ReparacionRepository $rr,UsuarioRepository $ur,Request $request): JsonResponse
    {  
        $data=json_decode($request->getContent(),true);
        $fecha = \DateTime::createFromFormat('d/m/Y', $data['fecha']);
        $descripcion=$data['descripcion'];
        
        //dd($data['fecha']);
        if ($fecha === false) {
            return $this->json(['error' => 'Fecha inicio inválida. El formato debe ser dd/MM/yyyy'], 400);
        }
        


        //dd($fecha);
        $usuario=$ur->findOneBy(['id'=>$data['usuario']]);

        if (!$usuario) {
            return $this->json(['error' => 'Usuario no encontrado'], 404);
        }
        $reparacion = new Reparacion($usuario, $fecha,$descripcion);
        $rr->add($reparacion);

        //$fechaString = $fecha->format('Y-m-d H:i:s');
        return $this->json(['id'=>$reparacion->getId()]);
    }

    #[Route('/ws/ticketRep', name: 'add_ticketRep', methods: ['POST'])]
    public function addTicketRep(TicketReparacionRepository $trr,ReparacionRepository $rr,ProductoRepository $pr,Request $request): JsonResponse
    {
        $data=json_decode($request->getContent(), true);
        $idProduto=$data['producto'];
        $idReparacion=$data['reparacion'];
        $producto=$pr->findOneBy(['id'=>$idProduto]);
        $reparacion=$rr->findOneBy(['id'=>$idReparacion]);
        $ticket=new TicketReparacion($reparacion,$producto);
        $trr->add($ticket);
    
    
        // Retornar la respuesta en formato JSON
        return $this->json(['message' => 'ticket creado']);
    }

    #[Route('/ws/productos', name: 'get_productos', methods: ['GET'])]
    public function getAllProductos(ProductoRepository $pr): JsonResponse
    {
        // Obtener todos los productos
        $productos = $pr->findAll();
    
    
        // Retornar la respuesta en formato JSON
        return $this->converToJson($pr->findAll());

    }
    
    
    
    private function converToJson($object):JsonResponse{
        $encoders=[new XmlEncoder(),new JsonEncoder()];
        $normalizers=[new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializers=new Serializer($normalizers,$encoders);
        $normalized=$serializers -> normalize($object,null,array(DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'));
        $jsonContent=$serializers->serialize($normalized,'json');
        return JsonResponse::fromJsonString($jsonContent,200);

    }
}
