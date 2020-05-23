<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LessonRepository::class)
 */
class Lesson
{
    public const PERIODICITY_MONTHLY = "monthly";
    public const PERIODICITY_WEEKLY = "weekly";
    public const PERIODICITY_EVERY_2_WEEK = "every 2 week";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"lesson:read"})
     */
    private $id;

    /**
     * @Assert\Length(min = 2, max = 255)
     * @ORM\Column(type="string", length=255)
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"lesson:read"})
     */
    private $master;

    /**
     * @ORM\ManyToOne(targetEntity=Platform::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $platform;

    /**
     * @Assert\Date
     * @ORM\Column(type="date")
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $startDate;

    /**
     * @Assert\Date
     * @ORM\Column(type="date")
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $endDate;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=SubGroup::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $subGroup;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $periodicity;

    /**
     * @Assert\Time
     * @ORM\Column(type="time")
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $startsAt;

    /**
     * @Assert\Time
     * @ORM\Column(type="time")
     * @Groups({"lesson:read", "lesson:write"})
     */
    private $endsAt;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMaster(): ?User
    {
        return $this->master;
    }

    public function setMaster(?User $master): self
    {
        $this->master = $master;

        return $this;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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

    public function getSubGroup(): ?SubGroup
    {
        return $this->subGroup;
    }

    public function setSubGroup(?SubGroup $subGroup): self
    {
        $this->subGroup = $subGroup;

        return $this;
    }

    public function getPeriodicity(): ?string
    {
        return $this->periodicity;
    }

    public function setPeriodicity(string $periodicity): self
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeInterface $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }
}
