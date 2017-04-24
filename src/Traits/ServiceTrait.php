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

        if (is_a($this->service, AbstractService::class, true)) {
            return new $this->service();
        }
    }
}
