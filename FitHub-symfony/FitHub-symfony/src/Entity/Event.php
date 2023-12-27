<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("events")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("events")]
    #[Assert\NotBlank(message: 'Please enter the event name')]
    #[Assert\Length(min: 5, max: 255, minMessage: 'The name of the event should be at least {{ limit }} characters long.', maxMessage: 'The name of the event should not exceed {{ limit }} characters.')]

    private ?string $nomEvent = null;

    #[ORM\Column(length: 255)]
    #[Groups("events")]
    #[Assert\NotBlank(message: 'Please enter the event description')]
    #[Assert\Length(min: 10, max: 255, minMessage: 'The description of the event should be at least {{ limit }} characters long.', maxMessage: 'The description of the event should not exceed {{ limit }} characters.')]

    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups("events")]
    #[Assert\NotBlank(message: 'Please enter the event location')]
    #[Assert\Length(min: 5, max: 255, minMessage: 'The location of the event should be at least {{ limit }} characters long.', maxMessage: 'The location of the event should not exceed {{ limit }} characters.')]

    private ?string $location = null;

    #[Assert\NotBlank(message: 'Please enter the event date and time')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("events")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups("events")]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Ticket::class, cascade: ["remove"])]
    private Collection $tickets;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups("events")]
    #[Assert\Image(
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Please upload a valid image (JPEG or PNG format)'
    )]
    private $image;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvent(): ?string
    {
        return $this->nomEvent;
    }

    public function setNomEvent(string $nomEvent): self
    {
        $this->nomEvent = $nomEvent;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getEvent() === $this) {
                $ticket->setEvent(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
    public function __toString()
{
    return $this->id;
}
}