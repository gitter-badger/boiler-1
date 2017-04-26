<?php

namespace Yakuzan\Boiler\Tests\Stubs\Entities;

use Yakuzan\Boiler\Entities\AbstractEntity;

class Guess extends AbstractEntity
{
    protected $connection = 'testbench';

    protected $table = 'guess';

    protected $fillable = ['title'];

    protected $access_rules = [
        'title'   => 'required|max:255',
    ];
}
