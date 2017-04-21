<?php

namespace Yakuzan\Boiler\Traits;

use Yakuzan\Boiler\Services\AbstractService;

trait ServiceTrait
{
    /** @var AbstractService */
    protected $service;

    /**
     * @param null $service
     *
     * @return $this|AbstractService
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

        if (is_string($this->service) && class_exists($this->service)) {
            return new $this->service();
        }
    }
}
