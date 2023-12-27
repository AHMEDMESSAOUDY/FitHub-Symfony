<?php

namespace App\Entity;

use App\Repository\ParticipationSeanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ParticipationSeanceRepository::class)]
class ParticipationSeance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('participations')]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups('participations')]
    private ?string $typeSport = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('participations')]
    private ?\DateTimeInterface $dateParticipation = null;


    #[ORM\ManyToOne(inversedBy: 'participation')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('participations')]
    private ?Utilisateur $participant = null;

    #[ORM\ManyToOne(inversedBy: 'participant')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('participations')]
    private ?Seance $seance = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeSport(): ?string
    {
        return $this->typeSport;
    }

    public function setTypeSport(string $typeSport): self
    {
        $this->typeSport = $typeSport;

        return $this;
    }

    public function getDateParticipation(): ?\DateTimeInterface
    {
        return $this->dateParticipation;
    }

    public function setDateParticipation(\DateTimeInterface $dateParticipation): self
    {
        $this->dateParticipation = $dateParticipation;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): self
    {
        $this->seance = $seance;

        return $this;
    }

    public function getParticipant(): ?Utilisateur
    {
        return $this->participant;
    }

    public function setParticipant(?Utilisateur $participant): self
    {
        $this->participant = $participant;

        return $this;
    }



}
