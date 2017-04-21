<?php

namespace Yakuzan\Boiler\Tests\Stubs\Entities;

use Yakuzan\Boiler\Entities\AbstractEntity;

class Lesson extends AbstractEntity
{
    protected $connection = 'testbench';

    protected $table = 'lessons';

    protected $fillable = ['title', 'subject'];
}
