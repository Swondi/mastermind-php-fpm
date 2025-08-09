<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?AuthUser $authUser = null;

    #[ORM\Column(length: 255)]
    private ?string $refreshToken = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expires_at = null;

    #[ORM\Column(length: 255)]
    private ?string $userAgent = null;

    #[ORM\Column(length: 15)]
    private ?string $ipAddress = null;

    public function __construct() {
        $this->refreshToken = bin2hex(random_bytes(32));
        $this->expires_at = (new DateTimeImmutable())->modify('+1 day');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthUser(): ?AuthUser
    {
        return $this->authUser;
    }

    public function setAuthUser(?AuthUser $authUser): static
    {
        $this->authUser = $authUser;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): static
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expires_at;
    }

    public function setExpiresAt(\DateTimeImmutable $expires_at): static
    {
        $this->expires_at = $expires_at;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }
}
