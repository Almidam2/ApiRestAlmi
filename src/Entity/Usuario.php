<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellido = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $contrasena = null;

    #[ORM\Column(length: 255)]
    private ?string $correo = null;

    #[ORM\Column(length: 255)]
    private ?string $imagen = null;

    #[ORM\Column]
    private ?int $rol = null;

    // Definir constantes para los roles
    const ROLE_USUARIO = 0; // Usuario normal
    const ROLE_TECNICO_CONSOLAS = 1; // Técnico de consolas
    const ROLE_TECNICO_OTROS_DISPOSITIVOS = 2; // Técnico de otros dispositivos
    const ROLE_ADMIN = 3; // Administrador total

    public function __construct(?string $correo, ?string $nombre, ?string $apellido, ?string $username, ?string $contrasena)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->username = $username;
        $this->contrasena = $contrasena;
        $this->correo = $correo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): static
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): static
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): static
    {
        $this->correo = $correo;

        return $this;
    }

    public function getRol(): ?int
    {
        return $this->rol;
    }

    public function setRol(int $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    // Método para verificar si el usuario tiene un rol específico
    public function hasRole(string $role): bool
    {
        switch ($role) {
            case 'ROLE_TECNICO_CONSOLAS':
                return $this->rol === self::ROLE_TECNICO_CONSOLAS;
            case 'ROLE_TECNICO_OTROS_DISPOSITIVOS':
                return $this->rol === self::ROLE_TECNICO_OTROS_DISPOSITIVOS;
            case 'ROLE_ADMIN':
                return $this->rol === self::ROLE_ADMIN;
            default:
                return $this->rol === self::ROLE_USUARIO;
        }
    }
}
