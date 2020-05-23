<?php

namespace App\Entity;

use App\Repository\SubGroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SubGroupRepository::class)
 */
class SubGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"sub-group:read", "lesson:write"})
     */
    private $id;

    /**
     * @Assert\Length(min = 2, max = 255)
     * @ORM\Column(type="string", length=255)
     * @Groups({"sub-group:read", "sub-group:write"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=BigGroup::class, inversedBy="subGroups")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"sub-group:read", "sub-group:write"})
     */
    private $bigGroup;

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

    public function getBigGroup(): ?BigGroup
    {
        return $this->bigGroup;
    }

    public function setBigGroup(?BigGroup $bigGroup): self
    {
        $this->bigGroup = $bigGroup;

        return $this;
    }
}
