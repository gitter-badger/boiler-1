<?php

namespace Yakuzan\Boiler\Services;

use Yakuzan\Boiler\Traits\EntityTrait;

abstract class AbstractService
{
    use EntityTrait;

    public function all()
    {
        $this->entity->get();
    }
}
