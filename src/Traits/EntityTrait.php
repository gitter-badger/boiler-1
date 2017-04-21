<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Entities\AbstractEntity;

trait EntityTrait
{
    /** @var AbstractEntity */
    protected $entity;

    /**
     * @return AbstractEntity
     */
    public function getEntity(): AbstractEntity
    {
        return $this->entity;
    }

    /**
     * @param AbstractEntity $entity
     *
     * @return $this
     */
    public function setEntity(AbstractEntity $entity)
    {
        $this->entity = $entity;

        return $this;
    }
}
