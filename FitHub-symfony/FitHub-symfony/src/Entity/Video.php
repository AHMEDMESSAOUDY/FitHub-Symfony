<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('videos')]
    private ?int $id = null;


    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('videos')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title;


    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups('videos')]
    #[Assert\Length(max: 5000)]
    private ?string $description;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('videos')]
    private ?string $video;

    #[Groups('videos')]
    #[Assert\File(maxSize: '4069M', mimeTypes: ['video/mp4', 'video/x-msvideo', 'video/x-flv', 'video/webm', 'video/x-m4v', 'video/quicktime', 'video/x-matroska', 'application/octet-stream'])]
    private ?File $videoFile = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'video')]
    #[Groups('videos')]
    #[Assert\NotBlank(message:"Merci de choisir une séance")]
    private ?Seance $seance = null;


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

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
    public function getVideoFile(): ?File
    {
        return $this->videoFile;
    }


    public function setVideoFile(?File $videoFile = null): self
    {
        $this->videoFile = $videoFile;
        if ($videoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
        return $this;
    }



}
