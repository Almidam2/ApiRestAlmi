<?php

namespace App\Entity;

use App\Repository\TicketAlquilerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketAlquilerRepository::class)]
class TicketAlquiler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Alquiler $alquiler = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Producto $producto = null;


    public function __construct(Alquiler $alq,?Producto $producto)
    {
        $this->alquiler=$alq;
        $this->producto=$producto;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlquiler(): ?Alquiler
    {
        return $this->alquiler;
    }

    public function setAlquiler(Alquiler $alquiler): static
    {
        $this->alquiler = $alquiler;

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
