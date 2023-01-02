<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $NumDevis = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDevis = null;


    #[ORM\OneToMany(mappedBy: 'Devis', targetEntity: LigneDevis::class)]
    private Collection $ligneDevis;

    #[ORM\ManyToOne(inversedBy: 'Devis')]
    private ?Client $client = null;

    public function __construct()
    {
        $this->ligneDevis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumDevis(): ?int
    {
        return $this->NumDevis;
    }

    public function setNumDevis(int $NumDevis): self
    {
        $this->NumDevis = $NumDevis;

        return $this;
    }

    public function getDateDevis(): ?\DateTimeInterface
    {
        return $this->DateDevis;
    }

    public function setDateDevis(\DateTimeInterface $DateDevis): self
    {
        $this->DateDevis = $DateDevis;

        return $this;
    }


    /**
     * @return Collection<int, LigneDevis>
     */
    public function getLigneDevis(): Collection
    {
        return $this->ligneDevis;
    }

    public function addLigneDevi(LigneDevis $ligneDevi): self
    {
        if (!$this->ligneDevis->contains($ligneDevi)) {
            $this->ligneDevis->add($ligneDevi);
            $ligneDevi->setDevis($this);
        }

        return $this;
    }

    public function removeLigneDevi(LigneDevis $ligneDevi): self
    {
        if ($this->ligneDevis->removeElement($ligneDevi)) {
            // set the owning side to null (unless already changed)
            if ($ligneDevi->getDevis() === $this) {
                $ligneDevi->setDevis(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
