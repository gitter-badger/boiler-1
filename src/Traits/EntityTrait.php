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
     * @return AbstractEntity|$this
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

        if (is_string($this->entity) && class_exists($this->entity)) {
            return new $this->entity();
        }
    }

    /**
     * @return string
     */
    public function entity_name()
    {
        if (is_string($this->entity) && class_exists($this->entity)) {
            return $this->entity;
        }

        if ($this->entity instanceof AbstractEntity) {
            return get_class($this->entity);
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
