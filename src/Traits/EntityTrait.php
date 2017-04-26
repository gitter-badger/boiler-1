<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Entities\AbstractEntity;

trait EntityTrait
{
    /** @var AbstractEntity */
    protected $entity;

    /**
     * @param null $entity
     *
     * @return AbstractEntity|EntityTrait
     */
    public function entity($entity = null)
    {
        if (null !== $entity) {
            $this->entity = $entity;

            return $this;
        }

        if ($this->entity instanceof AbstractEntity) {
            return $this->entity;
        }

        if (is_a($this->entity, AbstractEntity::class, true)) {
            return new $this->entity();
        }
    }

    /**
     * @return string
     */
    public function entity_name()
    {
        if ($this->entity instanceof AbstractEntity) {
            return get_class($this->entity);
        }

        if (is_a($this->entity, AbstractEntity::class, true)) {
            return $this->entity;
        }

        return '';
    }

    /**
     * @return string
     */
    public function entity_base_name()
    {
        return class_basename($this->entity_name());
    }
}
