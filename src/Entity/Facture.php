<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM; 
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?string $numero_facture = null;

    #[ORM\Column(length: 200)] 
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?string $designation = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?string $prix_designation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)] 
    // #[Assert\GreaterThan('today')]
    private ?\DateTimeInterface $date_facture = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?User $patient = null;

    #[ORM\OneToMany(mappedBy: 'vente', targetEntity: Bilan::class)]
    private Collection $bilans;

    public function __construct()
    {
        $this->bilans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroFacture(): ?string
    {
        return $this->numero_facture;
    }

    public function setNumeroFacture(string $numero_facture): self
    {
        $this->numero_facture = $numero_facture;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrixDesignation(): ?string
    {
        return $this->prix_designation;
    }

    public function setPrixDesignation(string $prix_designation): self
    {
        $this->prix_designation = $prix_designation;

        return $this;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->date_facture;
    }

    public function setDateFacture(\DateTimeInterface $date_facture): self
    {
        $this->date_facture = $date_facture;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient_name): self
    {
        $this->patient_name = $patient;

        return $this;
    }

    /**
     * @return Collection<int, Bilan>
     */
    public function getBilans(): Collection
    {
        return $this->bilans;
    }

    public function addBilan(Bilan $bilan): self
    {
        if (!$this->bilans->contains($bilan)) {
            $this->bilans->add($bilan);
            $bilan->setVente($this);
        }

        return $this;
    }

    public function removeBilan(Bilan $bilan): self
    {
        if ($this->bilans->removeElement($bilan)) {
            // set the owning side to null (unless already changed)
            if ($bilan->getVente() === $this) {
                $bilan->setVente(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return (string) $this-> getId();

        return (string) $this-> getPatient();
       


    }
}
