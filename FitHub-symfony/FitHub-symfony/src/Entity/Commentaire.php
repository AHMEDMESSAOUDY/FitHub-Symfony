<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

use Doctrine\ORM\Mapping as ORM;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCommentaire = null;
    //#[ORM\Column(type: Types::DATE_MUTABLE)]
    //8private ?\DateTimeInterface $descriptionCommentaire = null;

  

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[Assert\NotBlank(message : "vous devez choisir un article")]

    private ?Article $article = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message : "le contenu ne doit pas etre vide")]
    
    #[Assert\Length(max:100 , maxMessage : "le contenu du commentaire ne doit pas etre tros long ")]
    #[Assert\Length(min:2 , minMessage : "le contenu du commentaire ne doit pas etre inferieur a 2 caracteres  ")]


    private ?string $descriptionCommentaire = null;
    /**
     * @ORM\Column(type="text")
     * @AppAssert\BadWords
     */
    private $comment;

    #[ORM\Column(length: 255)]
    public ?string $nickname = null;

    #[ORM\Column(length: 255)]
    public ?string $email_c = null;

  

   

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $dateCommentaire): self
    {
        $this->dateCommentaire = $dateCommentaire;

        return $this;
    }

   
    

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getDescriptionCommentaire(): ?string
    {
        return $this->descriptionCommentaire;
    }

    public function setDescriptionCommentaire(string $descriptionCommentaire): self
    {
        $this->descriptionCommentaire = $descriptionCommentaire;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getEmailC(): ?string
    {
        return $this->email_c;
    }

    public function setEmailC(string $email_c): self
    {
        $this->email_c = $email_c;

        return $this;
    }

   

    
}
