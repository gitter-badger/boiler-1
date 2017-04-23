<?php

namespace Yakuzan\Boiler\Tests\Stubs\Entities;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $connection = 'testbench';

    protected $table = 'roles';
}
