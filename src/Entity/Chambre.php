<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private ?int $numero_chambre = null;

    #[ORM\ManyToOne(inversedBy: 'chambres')]
    private ?Service $service_affecter = null;

    #[ORM\OneToMany(mappedBy: 'chambre', targetEntity: Reservation::class)]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroChambre(): ?string
    {
        return $this->numero_chambre;
    }

    public function setNumeroChambre(string $numero_chambre): self
    {
        $this->numero_chambre = $numero_chambre;

        return $this;
    }

    public function getServiceAffecter(): ?Service
    {
        return $this->service_affecter;
    }

    public function setServiceAffecter(?Service $service_affecter): self
    {
        $this->service_affecter = $service_affecter;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setChambre($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getChambre() === $this) {
                $reservation->setChambre(null);
            }
        }

        return $this;
    }
    public function __toString(){
        

        return (string) $this-> getNumeroChambre();
       


    }
}
