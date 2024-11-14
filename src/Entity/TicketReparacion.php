<?php

namespace App\Entity;

use App\Repository\TicketReparacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketReparacionRepository::class)]
class TicketReparacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'ticketReparacion', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reparacion $reparacion = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Producto $producto = null;

    public function __construct(Reparacion $reparacion, Producto $producto)
    {
        $this->reparacion = $reparacion;
        $this->producto = $producto;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReparacion(): ?Reparacion
    {
        return $this->reparacion;
    }

    public function setReparacion(Reparacion $reparacion): static
    {
        $this->reparacion = $reparacion;

        return $this;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(Producto $producto): static
    {
        $this->producto = $producto;

        return $this;
    }
}