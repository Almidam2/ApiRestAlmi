<?php

// src/Entity/Venta.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTimeInterface;
use App\Repository\VentaRepository;

#[ORM\Entity(repositoryClass: VentaRepository::class)]
class Venta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Usuario", inversedBy: "ventas")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    // Relación con TicketCompra (OneToMany desde Venta hacia TicketCompra)
    #[ORM\OneToMany(mappedBy: 'venta', targetEntity: TicketCompra::class)]
    private $ticketCompras;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $precio = null;

    public function __construct(Usuario $usuario, ?DateTimeInterface $fecha, int $precio)
    {
        $this->usuario = $usuario;
        $this->fecha = $fecha;
        $this->precio = $precio;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(?int $precio): static
    {
        $this->precio = $precio;
        return $this;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getFecha(): ?DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;
        return $this;
    }

    public function getTicketCompras()
    {
        return $this->ticketCompras;
    }
}

