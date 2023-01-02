<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $NumArticle = null;

    #[ORM\Column(length: 255)]
    private ?string $Libelle = null;

    #[ORM\Column]
    private ?float $PrixUnitaire = null;

    #[ORM\Column]
    private ?int $QteStock = null;


    #[ORM\ManyToOne(inversedBy: 'Article')]
    private ?LigneDevis $ligneDevis = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumArticle(): ?int
    {
        return $this->NumArticle;
    }

    public function setNumArticle(int $NumArticle): self
    {
        $this->NumArticle = $NumArticle;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->PrixUnitaire;
    }

    public function setPrixUnitaire(float $PrixUnitaire): self
    {
        $this->PrixUnitaire = $PrixUnitaire;

        return $this;
    }

    public function getQteStock(): ?int
    {
        return $this->QteStock;
    }

    public function setQteStock(int $QteStock): self
    {
        $this->QteStock = $QteStock;

        return $this;
    }

    

    public function getLigneDevis(): ?LigneDevis
    {
        return $this->ligneDevis;
    }

    public function setLigneDevis(?LigneDevis $ligneDevis): self
    {
        $this->ligneDevis = $ligneDevis;

        return $this;
    }
}
