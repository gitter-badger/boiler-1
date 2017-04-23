<?php

namespace Yakuzan\Boiler\Tests\Stubs\Entities;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $connection = 'testbench';

    protected $table = 'permissions';
}
