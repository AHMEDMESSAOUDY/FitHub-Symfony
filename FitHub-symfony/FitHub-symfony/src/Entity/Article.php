<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message : "le titre ne doit pas etre vide")]
    #[Assert\Length(min:3, minMessage : "le nom ne doit pas etre inferieur a 5 caracteres")]
    #[Assert\Length(max:100, maxMessage : "le nom ne doit pas etre trop long")]

    private ?string $titreArticle = null;


   
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateArticle = null;

    #[ORM\Column(length: 255)]
    
    private ?string $imageArticle = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    
    #[Assert\NotBlank(message : "veuillez choisir une categorie")]

    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Commentaire::class,cascade: ["remove"])]
    private Collection $commentaires;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message : "la description ne doit pas etre vide")]

    private ?string $descriptionArticle = null;

    #[ORM\Column(nullable: true)]
    
    public ?int $like_article = 0;

    #[ORM\Column(nullable: true)]
    public ?int $dislike_article = 0;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreArticle(): ?string
    {
        return $this->titreArticle;
    }

    public function setTitreArticle(string $titreArticle): self
    {
        $this->titreArticle = $titreArticle;

        return $this;
    }


    public function getDateArticle(): ?\DateTimeInterface
    {
        return $this->dateArticle;
    }

    public function setDateArticle(\DateTimeInterface $dateArticle): self
    {
        $this->dateArticle = $dateArticle;

        return $this;
    }

    public function getImageArticle(): ?string
    {
        return $this->imageArticle;
    }

    public function setImageArticle(string $imageArticle): self
    {
        $this->imageArticle = $imageArticle;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }
    public function getCommentairesCount(): int
    {
        return $this->getCommentaires()->count();
    }


    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->titreArticle;
    }

    public function getDescriptionArticle(): ?string
    {
        return $this->descriptionArticle;
    }

    public function setDescriptionArticle(string $descriptionArticle): self
    {
        $this->descriptionArticle = $descriptionArticle;

        return $this;
    }

    public function getLikeArticle(): ?int
    {
        return $this->like_article;
    }

    public function setLikeArticle(?int $like_article): self
    {
        $this->like_article = $like_article;

        return $this;
    }

    public function getDislikeArticle(): ?int
    {
        return $this->dislike_article;
    }

    public function setDislikeArticle(?int $dislike_article): self
    {
        $this->dislike_article = $dislike_article;

        return $this;
    }
}
