<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Consultation;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\caterogy;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// #[UniqueEntity(
//     fields: ['consultation'],
//     errorPath: 'consultation',
//     message: 'This consultation is already in use.',
// )]
#[ORM\Entity(repositoryClass: FicheRepository::class)]
class Fiche implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 10,
        minMessage: 'Your decsription must be at least {{ limit }} characters long',
    )]
    #[Assert\NotBlank(message: "Please add your description")]
    private ?string $descriptionFiche = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        minMessage: 'Your name must be at least {{ limit }} characters long',
    )]
    #[Assert\NotBlank(message: "Please add your fiche name")]
    private ?string $nomFiche = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Please add your category")]
    private ?string $category = null;

    #[ORM\OneToOne(mappedBy: 'fiche', cascade: ['persist', 'remove'])]
    #[Assert\NotBlank(message: "Please add your consultation")]
    private ?Consultation $consultation = null;

    public function __toString()
    {
        return 'whatever you neet to see the type';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionFiche(): ?string
    {
        return $this->descriptionFiche;
    }

    public function setDescriptionFiche(string $descriptionFiche): self
    {
        $this->descriptionFiche = $descriptionFiche;

        return $this;
    }



    public function getNomFiche(): ?string
    {
        return $this->nomFiche;
    }

    public function setNomFiche(string $nomFiche): self
    {
        $this->nomFiche = $nomFiche;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $caterogy): self
    {
        if (!in_array($caterogy, [caterogy::NEW, caterogy::FOLLOWUP])) {
            throw new \InvalidArgumentException("Invalid type value");
        }

        $this->category = $caterogy;

        return $this;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function removeConsultations()
    {
        $this->consultation = null;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        // unset the owning side of the relation if necessary
        if ($consultation === null && $this->consultation !== null) {
            $this->consultation->setFiche(null);
        }

        // set the owning side of the relation if necessary
        if ($consultation !== null && $consultation->getFiche() !== $this) {
            $consultation->setFiche($this);
        }

        $this->consultation = $consultation;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'descriptionFiche' => $this->descriptionFiche,
            'nom' => $this->nomFiche,
            'category' => $this->category,
            'consultation' => $this->consultation
        );
    }

    public function constructor($descriptionFiche, $nom, $category)
    {
        $this->descriptionFiche = $descriptionFiche;
        $this->nomFiche = $nom;
        $this->category = $category;
    }
}
