<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="Ce pseudo est déjà utilisé")
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé")
 * @ORM\Table(name="`user`")
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
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=AntiPassoire::class, mappedBy="creator")
     */
    private $createdAntiPassoires;

    /**
     * @ORM\ManyToMany(targetEntity=AntiPassoire::class, mappedBy="contributors")
     */
    private $ContributedAntiPassoires;

    /**
     * @ORM\ManyToMany(targetEntity=CategoryGroup::class, inversedBy="users")
     */
    private $categoryGroups;

    /**
     * @ORM\OneToMany(targetEntity=CategoryGroup::class, mappedBy="creator")
     */
    private $categoryGroupsCreated;

    public function __construct()
    {
        $this->createdAntiPassoires = new ArrayCollection();
        $this->ContributedAntiPassoires = new ArrayCollection();
        $this->categoryGroups = new ArrayCollection();
        $this->categoryGroupsCreated = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, AntiPassoire>
     */
    public function getCreatedAntiPassoires(): Collection
    {
        return $this->createdAntiPassoires;
    }

    public function addCreatedAntiPassoire(AntiPassoire $createdAntiPassoire): self
    {
        if (!$this->createdAntiPassoires->contains($createdAntiPassoire)) {
            $this->createdAntiPassoires[] = $createdAntiPassoire;
            $createdAntiPassoire->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedAntiPassoire(AntiPassoire $createdAntiPassoire): self
    {
        if ($this->createdAntiPassoires->removeElement($createdAntiPassoire)) {
            // set the owning side to null (unless already changed)
            if ($createdAntiPassoire->getCreator() === $this) {
                $createdAntiPassoire->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AntiPassoire>
     */
    public function getContributedAntiPassoires(): Collection
    {
        return $this->ContributedAntiPassoires;
    }

    public function addContributedAntiPassoire(AntiPassoire $contributedAntiPassoire): self
    {
        if (!$this->ContributedAntiPassoires->contains($contributedAntiPassoire)) {
            $this->ContributedAntiPassoires[] = $contributedAntiPassoire;
            $contributedAntiPassoire->addContributor($this);
        }

        return $this;
    }

    public function removeContributedAntiPassoire(AntiPassoire $contributedAntiPassoire): self
    {
        if ($this->ContributedAntiPassoires->removeElement($contributedAntiPassoire)) {
            $contributedAntiPassoire->removeContributor($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CategoryGroup>
     */
    public function getCategoryGroups(): Collection
    {
        return $this->categoryGroups;
    }

    public function addCategoryGroup(CategoryGroup $categoryGroup): self
    {
        if (!$this->categoryGroups->contains($categoryGroup)) {
            $this->categoryGroups[] = $categoryGroup;
        }

        return $this;
    }

    public function removeCategoryGroup(CategoryGroup $categoryGroup): self
    {
        $this->categoryGroups->removeElement($categoryGroup);

        return $this;
    }

    /**
     * @return Collection<int, CategoryGroup>
     */
    public function getCategoryGroupsCreated(): Collection
    {
        return $this->categoryGroupsCreated;
    }

    public function addCategoryGroupsCreated(CategoryGroup $categoryGroupsCreated): self
    {
        if (!$this->categoryGroupsCreated->contains($categoryGroupsCreated)) {
            $this->categoryGroupsCreated[] = $categoryGroupsCreated;
            $categoryGroupsCreated->setCreator($this);
        }

        return $this;
    }

    public function removeCategoryGroupsCreated(CategoryGroup $categoryGroupsCreated): self
    {
        if ($this->categoryGroupsCreated->removeElement($categoryGroupsCreated)) {
            // set the owning side to null (unless already changed)
            if ($categoryGroupsCreated->getCreator() === $this) {
                $categoryGroupsCreated->setCreator(null);
            }
        }

        return $this;
    }
}
