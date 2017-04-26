<?php

namespace Yakuzan\Boiler\Tests\Stubs\Controllers;

use Yakuzan\Boiler\Controllers\AbstractApiController;
use Yakuzan\Boiler\Tests\Stubs\Entities\Guess;

class GuessController extends AbstractApiController
{
    protected $entity = Guess::class;
}
