<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message : "le nom ne doit pas etre vide")]
    #[Assert\Length(min:2,minMessage : "le nom ne doit pas etre inferieur a 5 caracteres")]
    #[Assert\Length(max:100,maxMessage : "le nom ne doit pas etre trop long")]

    private ?string $nomCategorie = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message : "la description ne doit pas etre vide")]

    #[Assert\Length(min:2 , minMessage : "la description doit avoir au minimum 2 caracteres")]
    private ?string $descriptionCategory = null;
    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Article::class,cascade: ["remove"])]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    public function getDescriptionCategory(): ?string
    {
        return $this->descriptionCategory;
    }

    public function setDescriptionCategory(string $descriptionCategory): self
    {
        $this->descriptionCategory = $descriptionCategory;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setCategorie($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategorie() === $this) {
                $article->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nomCategorie;
    }
}
