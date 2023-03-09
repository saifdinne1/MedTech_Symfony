<?php

namespace App\Entity;

use App\Repository\BilanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: BilanRepository::class)]
class Bilan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[ Assert\Expression(
        "this.getDateDebut() < this.getDateFin()",
       message:"La date fin ne doit pas être antérieure à la date début"
    )]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    #[Assert\Type('integer')]
    private ?int $cahrge = null;

    #[ORM\ManyToOne(inversedBy: 'bilans')]
    private ?Facture $vente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getCahrge(): ?string
    {
        return $this->cahrge;
    }

    public function setCahrge(string $cahrge): self
    {
        $this->cahrge = $cahrge;

        return $this;
    }

    public function getVente(): ?Facture
    {
        return $this->vente;
    }

    public function setVente(?Facture $vente): self
    {
        $this->vente = $vente;

        return $this;
    }
}
