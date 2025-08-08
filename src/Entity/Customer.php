<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, Workspace>
     */
    #[ORM\OneToMany(targetEntity: Workspace::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $workspaces;

    public function __construct()
    {
        $this->workspaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Workspace>
     */
    public function getWorkspaces(): Collection
    {
        return $this->workspaces;
    }

    public function addWorkspace(Workspace $workspace): static
    {
        if (!$this->workspaces->contains($workspace)) {
            $this->workspaces->add($workspace);
            $workspace->setOwner($this);
        }

        return $this;
    }

    public function removeWorkspace(Workspace $workspace): static
    {
        if ($this->workspaces->removeElement($workspace)) {
            // set the owning side to null (unless already changed)
            if ($workspace->getOwner() === $this) {
                $workspace->setOwner(null);
            }
        }

        return $this;
    }
}
