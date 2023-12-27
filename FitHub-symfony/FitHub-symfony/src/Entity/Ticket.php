<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("tickets")]

    private ?int $id = null;

    #[ORM\Column (nullable: true)]

    private ?bool $disponibilite = null;

    #[ORM\Column (nullable: true)]
    #[Groups("tickets")]
    #[Assert\Positive(message: 'The price must be a positive integer')]

    private ?int $prix = null;

    #[ORM\Column (nullable: true)]
    #[Groups("tickets")]
    private ?int $nombreMax = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[MaxDepth(1)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?Utilisateur $utilisateurs = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("tickets")]
   

   private ?string $nom = null;

    #[ORM\Column(length:255, nullable: true)]
    #[Groups("tickets")]
   private ?string $email = null;


    #[ORM\Column(type:"datetime",  nullable: true)]
    #[Groups("tickets")]
   private ?\DateTimeInterface $bookingDate = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function isDisponibilite(): ?bool
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(bool $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNombreMax(): ?int
    {
        return $this->nombreMax;
    }

    public function setNombreMax(int $nombreMax): self
    {
        $this->nombreMax = $nombreMax;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getUtilisateurs(): ?Utilisateur
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateur $utilisateurs): self
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBookingDate(): ?\DateTimeInterface
    {
        return $this->bookingDate;
    }

    public function setBookingDate(\DateTimeInterface $bookingDate): self
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }
    /*
    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }*/
    
}