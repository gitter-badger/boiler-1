<?php

namespace Yakuzan\Boiler\Tests\Stubs\Controllers;

use Yakuzan\Boiler\Controllers\AbstractApiController;
use Yakuzan\Boiler\Tests\Stubs\Services\LessonPolicyService;
use Yakuzan\Boiler\Tests\Stubs\Transformers\LessonTransformer;

class LessonPolicyApiController extends AbstractApiController
{
    protected $service = LessonPolicyService::class;

    protected $transformer = LessonTransformer::class;
}
