<?php

namespace App\Entity;

use App\Repository\VideoProgressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoProgressRepository::class)]
class VideoProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $progress = null;

    #[ORM\ManyToOne(inversedBy: 'videoProgress')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'videoProgress')]
    private ?Video $video = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastWatchedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgress(): ?float
    {
        return $this->progress;
    }

    public function setProgress(float $progress): static
    {
        $this->progress = $progress;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(?Video $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getLastWatchedAt(): ?\DateTimeInterface
    {
        return $this->lastWatchedAt;
    }

    public function setLastWatchedAt(\DateTimeInterface $lastWatchedAt): static
    {
        $this->lastWatchedAt = $lastWatchedAt;

        return $this;
    }
}
