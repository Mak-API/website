<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiEntityRepository")
 */
class ApiEntity
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Api", inversedBy="entities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $api;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiEntityField", mappedBy="entity", orphanRemoval=true)
     */
    private $fields;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApi(): ?Api
    {
        return $this->api;
    }

    public function setApi(?Api $api): self
    {
        $this->api = $api;

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
     * @return Collection|ApiEntityField[]
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(ApiEntityField $field): self
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field;
            $field->setEntity($this);
        }

        return $this;
    }

    public function removeField(ApiEntityField $field): self
    {
        if ($this->fields->contains($field)) {
            $this->fields->removeElement($field);
            // set the owning side to null (unless already changed)
            if ($field->getEntity() === $this) {
                $field->setEntity(null);
            }
        }

        return $this;
    }
}
