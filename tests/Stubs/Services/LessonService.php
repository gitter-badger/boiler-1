<?php

namespace Yakuzan\Boiler\Tests\Stubs\Services;

use Yakuzan\Boiler\Services\AbstractService;
use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;

class LessonService extends AbstractService
{
    protected $entity = Lesson::class;
}
