<?php

namespace App\Entity;

use App\Repository\AlquilerRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlquilerRepository::class)]
class Alquiler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $fecha_inicio = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $fecha_fin = null;

    #[ORM\OneToOne(mappedBy: 'alquiler', cascade: ['persist', 'remove'])]
    private ?TicketAlquiler $ticketAlquiler = null;

    #[ORM\Column]
    private ?int $precio = null;

    public function __construct(Usuario $usuario, ?DateTimeInterface $fecha_inicio, ?DateTimeInterface $fecha_fin,int $precio)
    {
        $this->usuario = $usuario;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
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

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getFechaInicio(): ?DateTimeInterface
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio(DateTimeInterface $fecha_inicio): static
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    public function getFechaFin(): ?DateTimeInterface
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(DateTimeInterface $fecha_fin): static
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    public function getTicketAlquiler(): ?TicketAlquiler
    {
        return $this->ticketAlquiler;
    }

    public function setTicketAlquiler(?TicketAlquiler $ticketAlquiler): static
    {
        // unset the owning side of the relation if necessar

        // set the owning side of the relation if necessary
        if ($ticketAlquiler !== null && $ticketAlquiler->getAlquiler() !== $this) {
            $ticketAlquiler->setAlquiler($this);
        }

        $this->ticketAlquiler = $ticketAlquiler;

        return $this;
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
}