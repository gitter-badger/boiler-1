<?php

namespace Yakuzan\Boiler\Services;

use Yakuzan\Boiler\Entities\AbstractEntity;

class DefaultService extends AbstractService
{
    /**
     * DefaultService constructor.
     *
     * @param AbstractEntity|string $entity
     */
    public function __construct($entity)
    {
        $this->entity($entity);
    }
}
