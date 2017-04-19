<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Entity\AbstractEntity;
use Yakuzan\Boiler\Services\AbstractService;

trait ControllerTrait
{
    protected $service = null;

    protected $entity = null;

    public function getService()
    {
        if ($this->service instanceof AbstractService) {
            return $this->service;
        }

        if (is_string($this->service) && class_exists($this->service)) {
            return new $this->service;
        }

        return null;
    }

    public function setService($service)
    {
        $this->service = $service;
    }

    public function getEntity()
    {
        if ($this->entity instanceof AbstractEntity) {
            return $this->entity;
        }

        if (is_string($this->entity) && class_exists($this->entity)) {
            return new $this->entity;
        }

        return null;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}
