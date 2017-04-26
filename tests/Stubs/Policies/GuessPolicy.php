<?php

namespace Yakuzan\Boiler\Tests\Stubs\Policies;

use Yakuzan\Boiler\Policies\AbstractPolicy;
use Yakuzan\Boiler\Tests\Stubs\Entities\Guess;

class GuessPolicy extends AbstractPolicy
{
    protected $entity = Guess::class;
}
