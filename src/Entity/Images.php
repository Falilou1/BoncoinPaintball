<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 */
class Images
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

     // Méthode magique __toString()
     public function __toString(): string
     {
         // Images sont stockées dans le dossier 'uploads/images/'
         return '/uploads/images/' . $this->name;
     }

    /**
     * @ORM\ManyToOne(targetEntity=Adverts::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adverts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdverts(): ?Adverts
    {
        return $this->adverts;
    }

    public function setAdverts(?Adverts $adverts): self
    {
        $this->adverts = $adverts;

        return $this;
    }
}
