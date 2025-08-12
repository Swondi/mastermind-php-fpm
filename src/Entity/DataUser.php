<?php

namespace App\Entity;
/**
 * {"server_overload_alerts":{"email":true,"mobile":false},"node_offline_warnings":{"email":true,"mobile":true},"high_latency_alerts":{"email":true,"mobile":false},"security_breach_detected":{"email":true,"mobile":false}}
 */
use App\Config\NotificationPreferencesConfig;
use App\Repository\DataUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DataUserRepository::class)]
class DataUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([ 'data' ])]
    private ?string $name = null;

    #[ORM\Column(length: 400, nullable: true)]
    #[Groups([ 'data' ])]
    private ?string $bio = null;

    #[ORM\Column(length: 40, nullable: true)]
    #[Groups([ 'data' ])]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([ 'data' ])]
    private ?string $profilePicture = null;

    #[ORM\Column(type: 'json')]
    #[Groups([ 'data' ])]
    private array $notificationPreference = [];

    #[ORM\OneToOne(mappedBy: 'dataUser', cascade: ['persist', 'remove'])]
    private ?AuthUser $authUser = null;

    public function __construct() {
        $this->notificationPreference = NotificationPreferencesConfig::ALL_PREFERENCES;
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

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getNotificationPreference(): array
    {
        return $this->notificationPreference;
    }

    public function setNotificationPreference(array $notificationPreference): static
    {
        $this->notificationPreference = $notificationPreference;

        return $this;
    }

    public function getAuthUser(): ?AuthUser
    {
        return $this->authUser;
    }

    public function setAuthUser(AuthUser $authUser): static
    {
        // set the owning side of the relation if necessary
        if ($authUser->getDataUser() !== $this) {
            $authUser->setDataUser($this);
        }

        $this->authUser = $authUser;

        return $this;
    }

    #[Groups([ 'data' ])]
    public function getEmail(): string
    {
        return $this->getAuthUser()->getEmail();
    }
}
