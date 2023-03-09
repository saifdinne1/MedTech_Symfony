<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'nom doit être au moins  {{ limit }} characteres ',
        maxMessage: 'nom ne doit pas depasser {{ limit }} characteres',
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'nom doit être au moins  {{ limit }} characteres ',
        maxMessage: 'nom ne doit pas depasser {{ limit }} characteres',
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: ' email {{ value }} est non valide. exmple : nomprenom@esprit.tn',
    )]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'numero doit être au moins  {{ limit }} characteres ',
        maxMessage: 'numero doit pas depasser {{ limit }} characteres',
    )]
    private ?int $tel = null;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Stock::class)]
    #[Assert\NotBlank]
    private Collection $Relation;

    public function __construct()
    {
        $this->Relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getRelation(): Collection
    {
        return $this->Relation;
    }

    public function addRelation(Stock $relation): self
    {
        if (!$this->Relation->contains($relation)) {
            $this->Relation->add($relation);
            $relation->setFournisseur($this);
        }

        return $this;
    }

    public function removeRelation(Stock $relation): self
    {
        if ($this->Relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getFournisseur() === $this) {
                $relation->setFournisseur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}