<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, VideoProgress>
     */
    #[ORM\OneToMany(targetEntity: VideoProgress::class, mappedBy: 'user')]
    private Collection $videoProgress;

    /**
     * @var Collection<int, Video>
     */
    #[ORM\ManyToMany(targetEntity: Video::class, inversedBy: 'watched')]
    #[ORM\JoinTable(name: 'user_videos_watched')]
    private Collection $watchedVideos;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $active = true;

    public function __construct()
    {
        $this->videoProgress = new ArrayCollection();
        $this->watchedVideos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, VideoProgress>
     */
    public function getVideoProgress(): Collection
    {
        return $this->videoProgress;
    }

    public function addVideoProgress(VideoProgress $videoProgress): static
    {
        if (!$this->videoProgress->contains($videoProgress)) {
            $this->videoProgress->add($videoProgress);
            $videoProgress->setUser($this);
        }

        return $this;
    }

    public function removeVideoProgress(VideoProgress $videoProgress): static
    {
        if ($this->videoProgress->removeElement($videoProgress)) {
            // set the owning side to null (unless already changed)
            if ($videoProgress->getUser() === $this) {
                $videoProgress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getWatchedVideos(): Collection
    {
        return $this->watchedVideos;
    }

    public function addWatchedVideo(Video $watchedVideo): static
    {
        if (!$this->watchedVideos->contains($watchedVideo)) {
            $this->watchedVideos->add($watchedVideo);
        }

        return $this;
    }

    public function hasWatchedVideo(Video $video): bool
    {
        return $this->watchedVideos->contains($video);
    }

    public function removeWatchedVideo(Video $watchedVideo): static
    {
        $this->watchedVideos->removeElement($watchedVideo);

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
