<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiEntityRelationRepository")
 */
class ApiEntityRelation
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ApiEntityField")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sourceEntityField;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ApiEntityField")
     * @ORM\JoinColumn(nullable=false)
     */
    private $targetEntityField;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="text")
     */
    private $attributes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceEntityField(): ?ApiEntityField
    {
        return $this->sourceEntityField;
    }

    public function setSourceEntityField(?ApiEntityField $sourceEntityField): self
    {
        $this->sourceEntityField = $sourceEntityField;

        return $this;
    }

    public function getTargetEntityField(): ?ApiEntityField
    {
        return $this->targetEntityField;
    }

    public function setTargetEntityField(?ApiEntityField $targetEntityField): self
    {
        $this->targetEntityField = $targetEntityField;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAttributes(): ?string
    {
        return $this->attributes;
    }

    public function setAttributes(string $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }
}
