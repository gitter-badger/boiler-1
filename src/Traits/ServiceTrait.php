<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Entities\AbstractEntity;
use Yakuzan\Boiler\Services\AbstractService;
use Yakuzan\Boiler\Services\DefaultService;

trait ServiceTrait
{
    /** @var AbstractService */
    protected $service;

    /**
     * @param null $service
     *
     * @return AbstractService|ServiceTrait
     */
    public function service($service = null)
    {
        if (null !== $service) {
            $this->service = $service;

            return $this;
        }

        if ($this->service instanceof AbstractService) {
            return $this->service;
        }

        if (is_a($this->service, AbstractService::class, true)) {
            return new $this->service();
        }

        return $this->guessFromEntityName();
    }

    /**
     * @return AbstractService|ServiceTrait
     */
    private function guessFromEntityName()
    {
        if ('' !== $entity = $this->entity_base_name()) {
            $service = config('boiler.services_namespace').'\\'.$entity.'Service';
            if (class_exists($service)) {
                $this->service = $service;

                $default = new $service();

                $default->entity($this->entity());

                return $default;
            }

            return new DefaultService($this->entity());
        }
    }

    /**
     * @return string
     */
    abstract public function entity_base_name();

    /**
     * @param string|AbstractEntity|null $entity
     *
     * @return AbstractEntity|$this
     */
    abstract public function entity($entity = null);
}
