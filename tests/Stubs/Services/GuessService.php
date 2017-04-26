<?php

namespace Yakuzan\Boiler\Tests\Stubs\Services;

use Yakuzan\Boiler\Services\AbstractService;
use Yakuzan\Boiler\Tests\Stubs\Entities\Guess;

class GuessService extends AbstractService
{
    protected $entity = Guess::class;
}
