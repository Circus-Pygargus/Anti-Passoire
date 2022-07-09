<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity(fields={"label"}, message="Ce nom de catégorie est déjà utilisé")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $label;

    /**
     * @Gedmo\Slug(fields={"label"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToMany(targetEntity=AntiPassoire::class, mappedBy="categories")
     */
    private $antiPassoires;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryGroup::class, inversedBy="categories")
     */
    private $categoryGroup;

    public function __construct()
    {
        $this->antiPassoires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, AntiPassoire>
     */
    public function getAntiPassoires(): Collection
    {
        return $this->antiPassoires;
    }

    public function addAntiPassoire(AntiPassoire $antiPassoire): self
    {
        if (!$this->antiPassoires->contains($antiPassoire)) {
            $this->antiPassoires[] = $antiPassoire;
            $antiPassoire->addCategory($this);
        }

        return $this;
    }

    public function removeAntiPassoire(AntiPassoire $antiPassoire): self
    {
        if ($this->antiPassoires->removeElement($antiPassoire)) {
            $antiPassoire->removeCategory($this);
        }

        return $this;
    }

    public function getCategoryGroup(): ?CategoryGroup
    {
        return $this->categoryGroup;
    }

    public function setCategoryGroup(?CategoryGroup $categoryGroup): self
    {
        $this->categoryGroup = $categoryGroup;

        return $this;
    }
}
