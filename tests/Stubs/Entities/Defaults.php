<?php

namespace Yakuzan\Boiler\Tests\Stubs\Entities;

use Yakuzan\Boiler\Entities\AbstractEntity;

class Defaults extends AbstractEntity
{
    protected $connection = 'testbench';

    protected $table = 'defaults';

    protected $fillable = ['title'];

    protected $access_rules = [
        'title'   => 'required|max:255',
    ];
}
