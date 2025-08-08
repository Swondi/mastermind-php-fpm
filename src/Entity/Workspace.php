<?php

namespace App\Entity;

use App\Repository\WorkspaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkspaceRepository::class)]
class Workspace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'workspaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $owner = null;

    /**
     * @var Collection<int, Node>
     */
    #[ORM\OneToMany(targetEntity: Node::class, mappedBy: 'workspace', orphanRemoval: true)]
    private Collection $nodes;

    public function __construct()
    {
        $this->nodes = new ArrayCollection();
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

    public function getOwner(): ?Customer
    {
        return $this->owner;
    }

    public function setOwner(?Customer $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Node>
     */
    public function getNodes(): Collection
    {
        return $this->nodes;
    }

    public function addNode(Node $node): static
    {
        if (!$this->nodes->contains($node)) {
            $this->nodes->add($node);
            $node->setWorkspace($this);
        }

        return $this;
    }

    public function removeNode(Node $node): static
    {
        if ($this->nodes->removeElement($node)) {
            // set the owning side to null (unless already changed)
            if ($node->getWorkspace() === $this) {
                $node->setWorkspace(null);
            }
        }

        return $this;
    }
}
