<?php

namespace Yakuzan\Boiler\Tests\Stubs\Services;

use Yakuzan\Boiler\Services\AbstractService;
use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;
use Yakuzan\Boiler\Tests\Stubs\Policies\LessonPolicy;

class LessonPolicyService extends AbstractService
{
    protected $entity = Lesson::class;

    protected $policy = LessonPolicy::class;
}
