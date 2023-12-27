<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Json;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface, \JsonSerializable
{
    
    //object relational mapping couche d'abstraction a la bdd 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotNull(message:"Merci de taper votre email")]
    #[Assert\Email(message:"***@***.***")]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"Merci de taper votre role")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]

    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Merci de taper votre nom")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Merci de taper votre prenom")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Merci de taper votre adresse")]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"Merci de taper votre telephone")]
    #[Assert\Positive]
    #[Assert\Length([
        'min' => 8,
        'max' => 8,
        'minMessage' => "Merci d'entrer 8 chiffres",
        'maxMessage' => "Merci d'entrer 8 chiffres"
])]
    private ?int $telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Merci d'entrer votre date de naissance")]
   
    private ?\DateTimeInterface $date_de_naissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Merci de choisir votre genre")]
    private ?string $genre = null;

    #[ORM\Column(nullable: true)]
  
    private ?float $poids = null;

    #[ORM\Column(nullable: true)]
    private ?float $taille = null;

    #[ORM\Column(nullable: true)]
    private ?int $experience = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialite = null;

    #[ORM\Column]
    private ?bool $IsBlocked = null;

    #[ORM\Column(nullable: true)]
    private ?bool $IsVerified = null;

    

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    //renvoie le sel utilisé pour hasher le mot de passe.
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    // efface toutes les données sensibles stockées sur l'objet utilisateur, telles qu'un mot de passe en texte clair.
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?int 
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $date_de_naissance): self
    {
        $this->date_de_naissance = $date_de_naissance;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(?float $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(?int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function isIsBlocked(): ?bool
    {
        return $this->IsBlocked;
    }

    public function setIsBlocked(bool $IsBlocked): self
    {
        $this->IsBlocked = $IsBlocked;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->IsVerified;
    }

    public function setIsVerified(?bool $IsVerified): self
    {
        $this->IsVerified = $IsVerified;

        return $this;
    }


    public function __toString(): string
    {
        $string = implode(',', $this->roles);
        if ($string == 'ROLE_NUTRISIONNISTE ') {
            return $this->nom;
        }
        return "NO NUTRISIONIST FOUND";
    }

    public function jsonSerialize(): array
    {
        $role = "ROLE_USER";

        if ($this->roles){
            $role = $this->roles[0];
        }

        return array(
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $role,
            'password' => $this->password,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'dateDeNaissance' => $this->date_de_naissance->format("d-m-Y"),
            'genre' => $this->genre     
        );
    }

    public function constructor($email, $roles, $nom, $prenom, $adresse, $telephone, $dateDeNaissance, $genre)
    {
        $this->email = $email;
        $this->roles = [$roles];
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->telephone = $telephone;
        $this->date_de_naissance = $dateDeNaissance;
        $this->genre = $genre;
    }
    
}
