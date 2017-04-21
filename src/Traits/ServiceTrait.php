<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Services\AbstractService;

trait ServiceTrait
{
    /** @var AbstractService */
    protected $service;

    /**
     * @return null|AbstractService
     */
    public function getService()
    {
        if ($this->service instanceof AbstractService) {
            return $this->service;
        }

        if (is_string($this->service) && class_exists($this->service)) {
            return new $this->service();
        }

        return null;
    }

    /**
     * @param AbstractService $service
     *
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }
}
