<?php

namespace App\Entity;

use App\Repository\SubGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubGroupRepository::class)
 */
class SubGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=BigGroup::class, inversedBy="subGroups")
     * @ORM\JoinColumn(nullable=false)
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
