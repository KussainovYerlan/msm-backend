<?php

namespace App\Entity;

use App\Repository\BigGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BigGroupRepository::class)
 */
class BigGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"id"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 2, max = 255)
     * @Groups({"big-group", "deserialize"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=SubGroup::class, mappedBy="bigGroup", orphanRemoval=true)
     */
    private $subGroups;

    public function __construct()
    {
        $this->subGroups = new ArrayCollection();
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

    /**
     * @return Collection|SubGroup[]
     */
    public function getSubGroups(): Collection
    {
        return $this->subGroups;
    }

    public function addSubGroup(SubGroup $subGroup): self
    {
        if (!$this->subGroups->contains($subGroup)) {
            $this->subGroups[] = $subGroup;
            $subGroup->setBigGroup($this);
        }

        return $this;
    }

    public function removeSubGroup(SubGroup $subGroup): self
    {
        if ($this->subGroups->contains($subGroup)) {
            $this->subGroups->removeElement($subGroup);
            // set the owning side to null (unless already changed)
            if ($subGroup->getBigGroup() === $this) {
                $subGroup->setBigGroup(null);
            }
        }

        return $this;
    }
}
