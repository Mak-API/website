<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DeletedTrait
{
    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $deleted = false;

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     *
     * @return DeletedTrait
     */
    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }
}
