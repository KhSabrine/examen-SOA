<?php

namespace App\Entity;

use App\Repository\LigneDevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneDevisRepository::class)]
class LigneDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[ORM\ManyToOne(inversedBy: 'ligneDevis')]
    private ?Devis $Devis = null;

    #[ORM\ManyToOne]
    private ?Article $Article = null;

    #[ORM\Column]
    private ?float $qte = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDevis(): ?Devis
    {
        return $this->Devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->Devis = $devis;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->Article;
    }

    public function setArticle(?Article $article): self
    {
        $this->Article = $article;

        return $this;
    }

    public function getQte(): ?float
    {
        return $this->qte;
    }

    public function setQte(float $qte): self
    {
        $this->qte = $qte;

        return $this;
    }
}
