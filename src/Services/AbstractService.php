<?php

namespace Yakuzan\Boiler\Services;

use Yakuzan\Boiler\Traits\EntityTrait;

abstract class AbstractService
{
    use EntityTrait;

    /**
     * @return array
     */
    public function all()
    {
        return $this->entity()->get();
    }
}
