<?php

namespace Yakuzan\Boiler\Tests\Stubs\Policies;

use Yakuzan\Boiler\Policies\AbstractPolicy;
use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;

class LessonPolicy extends AbstractPolicy
{
    protected $entity = Lesson::class;
}
