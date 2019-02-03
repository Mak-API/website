<?php

namespace App\Entity;

use App\Entity\Traits\DeletedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiRepository")
 */
class Api
{
    use TimestampableTrait;
    use DeletedTrait;

    /**
     * Status constants
     */
    const STATUS_DELETED = 0;
    const STATUS_INIT = 1;
    const STATUS_CREATED = 2;
    const STATUS_HOSTED = 3;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email_token;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $documentation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentPlan", inversedBy="apis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentPlan;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiRequest", mappedBy="api")
     */
    private $apiRequests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiEntity", mappedBy="api", orphanRemoval=true)
     */
    private $entities;

    public function __construct()
    {
        $this->apiRequests = new ArrayCollection();
        $this->entities = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEmailToken(): ?string
    {
        return $this->email_token;
    }

    public function setEmailToken(?string $email_token): self
    {
        $this->email_token = $email_token;

        return $this;
    }

    public function getDocumentation(): ?string
    {
        return $this->documentation;
    }

    public function setDocumentation(?string $documentation): self
    {
        $this->documentation = $documentation;

        return $this;
    }

    public function getPaymentPlan(): ?PaymentPlan
    {
        return $this->paymentPlan;
    }

    public function setPaymentPlan(?PaymentPlan $paymentPlan): self
    {
        $this->paymentPlan = $paymentPlan;

        return $this;
    }

    /**
     * @return Collection|ApiRequest[]
     */
    public function getApiRequests(): Collection
    {
        return $this->apiRequests;
    }

    public function addApiRequest(ApiRequest $apiRequest): self
    {
        if (!$this->apiRequests->contains($apiRequest)) {
            $this->apiRequests[] = $apiRequest;
            $apiRequest->setApi($this);
        }

        return $this;
    }

    public function removeApiRequest(ApiRequest $apiRequest): self
    {
        if ($this->apiRequests->contains($apiRequest)) {
            $this->apiRequests->removeElement($apiRequest);
            // set the owning side to null (unless already changed)
            if ($apiRequest->getApi() === $this) {
                $apiRequest->setApi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApiEntity[]
     */
    public function getEntities(): Collection
    {
        return $this->entities;
    }

    public function addEntity(ApiEntity $entity): self
    {
        if (!$this->entities->contains($entity)) {
            $this->entities[] = $entity;
            $entity->setApi($this);
        }

        return $this;
    }

    public function removeEntity(ApiEntity $entity): self
    {
        if ($this->entities->contains($entity)) {
            $this->entities->removeElement($entity);
            // set the owning side to null (unless already changed)
            if ($entity->getApi() === $this) {
                $entity->setApi(null);
            }
        }

        return $this;
    }
}
