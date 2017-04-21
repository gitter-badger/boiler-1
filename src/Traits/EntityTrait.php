<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Entities\AbstractEntity;

trait EntityTrait
{
    /** @var AbstractEntity */
    protected $entity;

    public function entity($entity = null)
    {
        if (null !== $entity) {
            $this->entity = $entity;

            return $this;
        }

        if ($this->entity instanceof AbstractEntity) {
            return $this->entity;
        }

        if (is_string($this->entity) && class_exists($this->entity)) {
            return new $this->entity();
        }
    }
}
