<?php

namespace App\Entity;

use App\Repository\AdvertsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdvertsRepository::class)
 */
class Adverts
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    public static array $CATEGORIES = [
        'Accessoires pour lanceurs',
        'Air comprimé et CO2',
        'Bagages et housses',
        'Canons',
        'Covoiturage',
        'Divers',
        'Kits et packages',
        'Lanceurs de scénario',
        'Lanceur de compétition',
        'Lanceurs de loisir',
        'Loaders et accessoires',
        'Masques et écrans',
        'Recrutements',
        'Tournois',
        'Terrains et accessoires',
        'Vetements de jeu',
    ];

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brand;

    public static array $BRANDS = [
        'Autres marques',
        'Armotech',
        'Azodin',
        'Base',
        'BT',
        'Bunker Kings',
        'Deadlywind',
        'DLX',
        'Dye',
        'Empire',
        'GI Sportz/V-Force',
        'HK Army',
        'Honorcore',
        'JT',
        'Lapco',
        'MacDev',
        'Milsig',
        'Oubtreak',
        'Planet Eclipse',
        'Powair',
        'Proto',
        'Sly',
        'Smart Parts/GOG',
        'Soger',
        'Spyder',
        'Tiberius',
        'Tippmann/Hammerhead',
        'Trident',
        'Valken',
        'Virtue',
        'WGP',
    ];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $useCondition;

    public static array $USECONDITIONS = [
        'Neuf',
        'Très bon état',
        'Bon état',
        'Satisfaisant',
        'Pour pièces',
    ];

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public static array $STATUS = [
        "En cours",
        "Vendues",
        "Abandonnées",
    ];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    public static array $REGIONS = [
        'Auvergne-Rhône-Alpes',
        'Bourgogne-Franche-Comté',
        'Bretagne',
        'Centre-Val de Loire',
        'Corse',
        'Grand Est',
        'Hauts-de-France',
        'Ile-de-France',
        'Normandie',
        'Nouvelle-Aquitaine',
        'Occitanie',
        'Pays de la Loire',
        'Provence-Alpes-Côte d’Azur',
    ];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adverts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getUseCondition(): ?string
    {
        return $this->useCondition;
    }

    public function setUseCondition(string $useCondition): self
    {
        $this->useCondition = $useCondition;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
