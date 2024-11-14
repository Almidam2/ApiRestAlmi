<?php

namespace App\Entity;

use App\Repository\MicroPostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MicroPostRepository::class)]
class MicroPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?Usuario
    {
        return $this->author;
    }

    public function setAuthor(?Usuario $author): static
    {
        $this->author = $author;

        return $this;
    }
}
