<?php

namespace Yakuzan\Boiler\Tests\Stubs\Controllers;

use Yakuzan\Boiler\Controllers\AbstractApiController;
use Yakuzan\Boiler\Tests\Stubs\Entities\Defaults;

class DefaultsController extends AbstractApiController
{
    protected $entity = Defaults::class;
}
