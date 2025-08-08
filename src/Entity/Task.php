<?php

namespace App\Entity;

use App\Enum\TaskStatus;
use App\Enum\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\Column]
    private Uuid $id;

    #[ORM\Column(enumType: TaskType::class)]
    private ?TaskType $type = null;

    #[ORM\Column]
    private array $payload = [];

    #[ORM\Column(enumType: TaskStatus::class)]
    private ?TaskStatus $status = null;

    #[ORM\Column(nullable: true)]
    private ?array $error = null;
    
    #[ORM\Column(nullable: true)]
    private ?array $output = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finished_at = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Node $node = null;

    public function __construct() {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getType(): ?TaskType
    {
        return $this->type;
    }

    public function setType(TaskType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    public function getStatus(): ?TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getError(): ?array
    {
        return $this->error;
    }

    public function setError(?array $error): static
    {
        $this->error = $error;

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

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finished_at;
    }

    public function setFinishedAt(?\DateTimeImmutable $finished_at): static
    {
        $this->finished_at = $finished_at;

        return $this;
    }

    public function getNode(): ?Node
    {
        return $this->node;
    }

    public function setNode(?Node $node): static
    {
        $this->node = $node;

        return $this;
    }

    public function getOutput(): ?array
    {
        return $this->output;
    }

    public function setOutput(?array $output): static
    {
        $this->output = $output;

        return $this;
    }
}
