<?php

namespace App\Entity;

use App\Repository\ReparacionRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReparacionRepository::class)]
class Reparacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 500)]
    private ?string $descripcion = null;

    #[ORM\OneToOne(mappedBy: 'reparacion', cascade: ['persist', 'remove'])]
    private ?TicketReparacion $ticketReparacion = null;

    public function __construct(Usuario $usuario,?DateTimeInterface $fecha,String $descripcion )
    {
        $this->usuario=$usuario;
        $this->fecha=$fecha;
        $this->descripcion=$descripcion;
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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTicketReparacion(): ?TicketReparacion
    {
        return $this->ticketReparacion;
    }

    public function setTicketReparacion(?TicketReparacion $ticketReparacion): static
    {
        // set the owning side of the relation if necessary
        if ($ticketReparacion !== null && $ticketReparacion->getReparacion() !== $this) {
            $ticketReparacion->setReparacion($this);
        }

        $this->ticketReparacion = $ticketReparacion;

        return $this;
    }
}
