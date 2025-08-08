<?php

namespace App\Entity;

use App\Repository\MetricRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MetricRepository::class)]
class Metric
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $cpuUsage = null;

    #[ORM\Column]
    private ?float $ram = null;

    #[ORM\Column]
    private ?float $disk = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $timestamp = null;

    #[ORM\ManyToOne(inversedBy: 'metrics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Node $node = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCpuUsage(): ?float
    {
        return $this->cpuUsage;
    }

    public function setCpuUsage(float $cpuUsage): static
    {
        $this->cpuUsage = $cpuUsage;

        return $this;
    }

    public function getRam(): ?float
    {
        return $this->ram;
    }

    public function setRam(float $ram): static
    {
        $this->ram = $ram;

        return $this;
    }

    public function getDisk(): ?float
    {
        return $this->disk;
    }

    public function setDisk(float $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeImmutable $timestamp): static
    {
        $this->timestamp = $timestamp;

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
}
