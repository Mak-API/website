<?php

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;

trait TimestampableTrait
{
    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var User|null $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @MaxDepth(2)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    /**
     * @var User|null $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @MaxDepth(2)
     */
    private $updatedBy;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return TimestampableTrait
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @MaxDepth(2)
     * @return User|null
     */
    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface|null $createdBy
     *
     * @return TimestampableTrait
     */
    public function setCreatedBy(?UserInterface $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return TimestampableTrait
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @MaxDepth(2)
     * @return User|null
     */
    public function getUpdatedBy(): ?UserInterface
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface|null $updatedBy
     *
     * @return TimestampableTrait
     */
    public function setUpdatedBy(?UserInterface $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}