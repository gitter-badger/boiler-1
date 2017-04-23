<?php

namespace Yakuzan\Boiler\Tests\Stubs\Entities;

use Yakuzan\Boiler\Entities\AbstractEntity;

class Lesson extends AbstractEntity
{
    protected $connection = 'testbench';

    protected $table = 'lessons';

    protected $fillable = ['title', 'subject'];

    protected $access_rules = [
        'title'   => 'required|max:255',
        'subject' => 'required|max:255',
    ];
}
