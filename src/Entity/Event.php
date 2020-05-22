<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"id"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Platform::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"event", "deserialize"})
     */
    private $platform;

    /**
     * @Assert\DateTime
     * @ORM\Column(type="datetime")
     * @Groups({"event", "deserialize"})
     */
    private $startingAt;

    /**
     * @Assert\Length(min = 2, max = 2048)
     * @ORM\Column(type="text")
     * @Groups({"event", "deserialize"})
     */
    private $description;

    /**
     * @Assert\Length(min = 2, max = 255)
     * @ORM\Column(type="string", length=255)
     * @Groups({"event", "deserialize"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"event", "deserialize"})
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"event"})
     */
    private $master;

    /**
     * @Assert\DateTime
     * @ORM\Column(type="datetime")
     * @Groups({"event", "deserialize"})
     */
    private $endingAt;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function setPlatform(?Platform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getStartingAt(): ?\DateTimeInterface
    {
        return $this->startingAt;
    }

    public function setStartingAt(\DateTimeInterface $startingAt): self
    {
        $this->startingAt = $startingAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
        }

        return $this;
    }

    public function getMaster(): ?User
    {
        return $this->master;
    }

    public function setMaster(?User $master): self
    {
        $this->master = $master;

        return $this;
    }

    public function getEndingAt(): ?\DateTimeInterface
    {
        return $this->endingAt;
    }

    public function setEndingAt(\DateTimeInterface $endingAt): self
    {
        $this->endingAt = $endingAt;

        return $this;
    }
}
