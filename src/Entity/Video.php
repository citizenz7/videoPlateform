<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    /**
     * @var Collection<int, VideoProgress>
     */
    #[ORM\OneToMany(targetEntity: VideoProgress::class, mappedBy: 'video')]
    private Collection $videoProgress;

    #[ORM\Column]
    private ?float $duration = null;

    public function __construct()
    {
        $this->videoProgress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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
            $videoProgress->setVideo($this);
        }

        return $this;
    }

    public function removeVideoProgress(VideoProgress $videoProgress): static
    {
        if ($this->videoProgress->removeElement($videoProgress)) {
            // set the owning side to null (unless already changed)
            if ($videoProgress->getVideo() === $this) {
                $videoProgress->setVideo(null);
            }
        }

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}
