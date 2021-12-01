<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"pseudo"}, message="Un compte existe déjà avec ce pseudo !", groups={"register", "monProfil"})
 * @UniqueEntity(fields={"email"}, message="Un compte existe déjà avec cet email !", groups={"register", "monProfil"})
 */

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="L'email est requise !", groups={"register", "monProfil"})
     * @Assert\Email(message="L'email est invalide !", groups={"register", "monProfil"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string|null The hashed password
     * @Assert\NotBlank(message="Le mot de passe est requis !")
     * @Assert\Length(min=8, max=50, minMessage="Le mot de passe doit contenir au minimum {{ limit }} caractères", maxMessage="Le mot de passe doit contenir au maximum {{ limit }} caractères")
     */
    private ?string $plainPassword;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le prenom est requis !", groups={"register"})
     * @Assert\Length(min=2, max=50, minMessage="Le prenom doit contenir au minimum {{ limit }} caractères", maxMessage="Le prenom doit contenir au maximum {{ limit }} caractères", groups={"register"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(min=10, max=10, minMessage="Le numéro de téléphone doit contenir au minimum {{ limit }} caractères", maxMessage="Le numéro de téléphone doit contenir au maximum {{ limit }} caractères")
     * @Assert\Regex(pattern="/^(\(0\))?[0-9]+$/", message="number_only")
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     *
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="user", cascade={"remove"})
     */
    private $inscriptions;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Le pseudo est requis !", groups={"register", "monProfil"})
     * @Assert\Length(min=2, max=50, minMessage="Le pseudo doit contenir au minimum {{ limit }} caractères", maxMessage="Le pseudo doit contenir au maximum {{ limit }} caractères", groups={"register"})
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", cascade={"remove"})
     */
    private $sortieOrganisee;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le nom est requis !", groups={"register"})
     * @Assert\Length(min=2, max=50, minMessage="Le nom doit contenir au minimum {{ limit }} caractères", maxMessage="Le nom doit contenir au maximum {{ limit }} caractères", groups={"register"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private $imageFilename;

    /**
     * @return mixed
     */
    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    /**
     * @param mixed $imageFilename
     */
    public function setImageFilename($imageFilename): void
    {
        $this->imageFilename = $imageFilename;
    }


    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->sortieOrganisee = new ArrayCollection();

    }

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
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
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
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setUser($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getUser() === $this) {
                $inscription->setUser(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortieOrganisee(): Collection
    {
        return $this->sortieOrganisee;
    }

    public function addSortieOrganisee(Sortie $sortieOrganisee): self
    {
        if (!$this->sortieOrganisee->contains($sortieOrganisee)) {
            $this->sortieOrganisee[] = $sortieOrganisee;
            $sortieOrganisee->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortieOrganisee(Sortie $sortieOrganisee): self
    {
        if ($this->sortieOrganisee->removeElement($sortieOrganisee)) {
            // set the owning side to null (unless already changed)
            if ($sortieOrganisee->getOrganisateur() === $this) {
                $sortieOrganisee->setOrganisateur(null);
            }
        }

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

    public function __toString()
    {
        return $this->getPseudo();
        return $this->imageFilename;
    }

}

