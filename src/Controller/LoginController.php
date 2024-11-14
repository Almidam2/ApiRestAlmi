<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginController extends AbstractController


{
    #[Route('/login', name: 'app_login' , methods: ['GET']), ]
     public function index(AuthenticationUtils $authenticationUtils, TokenStorageInterface $tokenStorage)
    {
        // Si el usuario ya está autenticado, lo redirigimos a la página principal del panel de administración
        if ($tokenStorage->getToken()) {
            return $this->redirectToRoute('productos'); // Asegúrate de que esta ruta exista
        }

        // Obtenemos el error de autenticación y el último nombre de usuario ingresado
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Renderizamos la vista del formulario de login
        return $this->render('base.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/login/verify', name: 'app_login_verify', methods: ['POST', 'PUT'])]
public function login(
    Request $request,
    EntityManagerInterface $entityManager,
    UsuarioRepository $ur
): Response {
    $usernameOrEmail = $request->request->get('usernameOrEmail'); // Obtener username o email
    $contrasena = $request->request->get('password');
    
    // Verificar si el valor recibido es un correo o un nombre de usuario
    if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
        // Si es un correo electrónico
        $usuario = $ur->findOneBy(['correo' => $usernameOrEmail]);
    } else {
        // Si no es un correo, consideramos que es un nombre de usuario
        $usuario = $ur->findOneBy(['username' => $usernameOrEmail]);
    }

    // Verificar si el usuario existe
    if ($usuario == null) {
        return $this->render('base.html.twig', [
            'last_username' => $usernameOrEmail,
            'error' => 'Credenciales inválidas',
        ]);
    }

    // Validar la contraseña
    if (!$this->valido($usuario, $contrasena)) {
        return $this->render('base.html.twig', [
            'last_username' => $usernameOrEmail,
            'error' => 'Credenciales incorrectas.',
        ]);
    }

    // Verificar que el rol sea 1, 2 o 3 (técnicos y administrador)
    if (!in_array($usuario->getRol(), [1, 2, 3])) {
        return $this->render('base.html.twig', [
            'last_username' => $usernameOrEmail,
            'error' => 'Acceso denegado: no tiene permisos suficientes.',
        ]);
    }

    switch ($usuario->getRol()) {
        case 1:
            return $this->redirectToRoute('productos_consolas');  // Si rol 1, redirigir a /productos/consolas
        case 2:
            return $this->redirectToRoute('productos_otros_dispositivos');  // Si rol 2, redirigir a /productos/otros_dispositivos
        case 3:
            return $this->redirectToRoute('productos');  // Si rol 3, redirigir a /productos
        default:
            // Si el rol no es válido, redirigir a la página principal o alguna página de error
            return $this->redirectToRoute('home');
    }

    
}



    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        // Symfony maneja el cierre de sesión automáticamente, este método puede quedarse vacío
    }
    public function valido(Usuario $user, string $contrasena): bool
    {
        if($user===null){
            return false;
        }
        if($user->getContrasena()===$contrasena){
            return true;
        }
        return false;
    }

    
}
