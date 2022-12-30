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

    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'devis')]
    private Collection $Article;

    public function __construct()
    {
        $this->Article = new ArrayCollection();
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
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->Article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->Article->contains($article)) {
            $this->Article->add($article);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->Article->removeElement($article);

        return $this;
    }
}
