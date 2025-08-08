<?php

namespace App\Entity;

use App\Enum\NodeStatus;
use App\Repository\NodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NodeRepository::class)]
class Node
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    #[ORM\Column(length: 32)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'nodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workspace $workspace = null;

    #[ORM\Column(length: 255)]
    private ?string $apiKey = null;

    #[ORM\Column(enumType: NodeStatus::class)]
    private ?NodeStatus $status = null;

    /**
     * @var Collection<int, Metric>
     */
    #[ORM\OneToMany(targetEntity: Metric::class, mappedBy: 'node', orphanRemoval: true)]
    private Collection $metrics;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'node', orphanRemoval: true)]
    private Collection $tasks;

    public function __construct()
    {
        $this->metrics = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getWorkspace(): ?Workspace
    {
        return $this->workspace;
    }

    public function setWorkspace(?Workspace $workspace): static
    {
        $this->workspace = $workspace;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getStatus(): ?NodeStatus
    {
        return $this->status;
    }

    public function setStatus(NodeStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Metric>
     */
    public function getMetrics(): Collection
    {
        return $this->metrics;
    }

    public function addMetric(Metric $metric): static
    {
        if (!$this->metrics->contains($metric)) {
            $this->metrics->add($metric);
            $metric->setNode($this);
        }

        return $this;
    }

    public function removeMetric(Metric $metric): static
    {
        if ($this->metrics->removeElement($metric)) {
            // set the owning side to null (unless already changed)
            if ($metric->getNode() === $this) {
                $metric->setNode(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setNode($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getNode() === $this) {
                $task->setNode(null);
            }
        }

        return $this;
    }
}
