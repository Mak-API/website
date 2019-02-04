<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentPlanRepository")
 */
class PaymentPlan
{
    use TimestampableTrait;

    const PERIODICITY_DAILY = 1;
    const PERIODICITY_WEEKLY = 2;
    const PERIODICITY_MONTHLY = 3;
    const PERIODICITY_BIYEARLY = 4;
    const PERIODICITY_YEARLY = 5;

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
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $requests;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $periodicity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Api", mappedBy="paymentPlan")
     */
    private $apis;

    public function __construct()
    {
        $this->apis = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRequests(): ?int
    {
        return $this->requests;
    }

    public function setRequests(int $requests): self
    {
        $this->requests = $requests;

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

    /**
     * @return Collection|Api[]
     */
    public function getApis(): Collection
    {
        return $this->apis;
    }

    public function addApi(Api $api): self
    {
        if (!$this->apis->contains($api)) {
            $this->apis[] = $api;
            $api->setPaymentPlan($this);
        }

        return $this;
    }

    public function removeApi(Api $api): self
    {
        if ($this->apis->contains($api)) {
            $this->apis->removeElement($api);
            // set the owning side to null (unless already changed)
            if ($api->getPaymentPlan() === $this) {
                $api->setPaymentPlan(null);
            }
        }

        return $this;
    }
}
