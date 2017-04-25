<?php

namespace Yakuzan\Boiler\Tests\Stubs\Controllers;

use Yakuzan\Boiler\Controllers\AbstractApiController;
use Yakuzan\Boiler\Tests\Stubs\Services\LessonService;
use Yakuzan\Boiler\Tests\Stubs\Transformers\LessonTransformer;

class LessonBlacklistApiController extends AbstractApiController
{
    protected $service = LessonService::class;

    protected $transformer = LessonTransformer::class;

    protected $blacklist = ['index', 'delete'];
}
