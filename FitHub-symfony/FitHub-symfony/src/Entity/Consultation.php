<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use App\Enum\typeConsultation;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Fiche;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    //#[Assert\NotBlank(message:"Please add your appointement date")]
    #[Assert\GreaterThan("today")]
    private ?\DateTimeInterface $dateConsultation = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    // #[Assert\Range(
    //     min: '06:00',
    //     max: '22:00',
    // )]
    #[Assert\NotBlank(message:"Please add your appointement time")]
    private ?\DateTimeInterface $heureConsultation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Please add your appointement type")]
    private ?string $typeConsultation = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        minMessage: 'Your appointement name must be at least {{ limit }} characters long',
    )]
    #[Assert\NotBlank(message:"Please add your appointement name")]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Expression(
        "this.getUtilisateur()!= 'NO NUTRISIONIST FOUND'",
        message: 'UNVALID! Please choose a nutritionist',
    )]
    #[Assert\NotBlank(message:"Please add your nutritionist")]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToOne(inversedBy: 'consultation', cascade: ['persist', 'remove'])]
    private ?Fiche $fiche = null;


    public function __construct()
    {
        $this->dateConsultation = new \DateTime();
        $this->heureConsultation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateConsultation(): ?\DateTimeInterface
    {
        return $this->dateConsultation;
    }

    public function setDateConsultation(\DateTimeInterface $dateConsultation): self
    {
        $this->dateConsultation = $dateConsultation;

        return $this;
    }

    public function getHeureConsultation(): ?\DateTimeInterface
    {
        return $this->heureConsultation;
    }

    public function setHeureConsultation(\DateTimeInterface $heureConsultation): self
    {
        $this->heureConsultation = $heureConsultation;

        return $this;
    }

    public function getTypeConsultation(): ?string
    {
        return $this->typeConsultation;
    }

    public function setTypeConsultation(string $typeConsultation): self
    {
        if (!in_array($typeConsultation, [typeConsultation::ADISTANCE, typeConsultation::CABINET])) {
            throw new \InvalidArgumentException("Invalid type value");
        }

        $this->typeConsultation = $typeConsultation;

        return $this;
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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
  }

    public function getFiche(): ?Fiche
    {
        return $this->fiche;
    }

    public function setFiche(?Fiche $fiche): self
    {
        $this->fiche = $fiche;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'utilisateur' => $this->utilisateur,
            'date' => $this->dateConsultation->format("d-m-Y"),
            'heure' => $this->heureConsultation->format("d-m-Y"),
            'type' => $this->typeConsultation,
            'nom' => $this->nom
        );
    }

    public function constructor($utilisateur, $date, $heure, $type, $nom)
    {
        $this->utilisateur = $utilisateur;
        $this->dateConsultation = $date;
        $this->heureConsultation = $heure;
        $this->typeConsultation = $type;
        $this->nom = $nom;
    }
}
