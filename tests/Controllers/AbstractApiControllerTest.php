<?php

namespace Yakuzan\Boiler\Tests\Controllers;

use Illuminate\Http\Request;
use Laracasts\TestDummy\Factory;
use Yakuzan\Boiler\Controllers\AbstractApiController;
use Yakuzan\Boiler\Tests\Stubs\Controllers\LessonApiController;
use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;
use Yakuzan\Boiler\Tests\TestCase;

class AbstractApiControllerTest extends TestCase
{
    /** @var AbstractApiController $controller */
    protected $controller;

    protected function setUp()
    {
        parent::setUp();

        $this->controller = new LessonApiController();
    }

    /** @test */
    public function it_return_pagination_with_api_response()
    {
        $lessons = Factory::times(10)->create(Lesson::class)->all();
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->index(new Request());

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertCount($result->getData(true)['meta']['pagination']['total'], $lessons);
    }

    /** @test */
    public function it_return_response_not_found_when_lessons_table_empty()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->index(new Request());
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_return_with_id()
    {
        $lessons = Factory::times(10)->create(Lesson::class)->all();
        $lesson = next($lessons);
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->show($lesson->id);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($lesson->id, $result->getData(true)['data']['id']);
    }

    /** @test */
    public function it_return_response_not_found_when_lesson_not_found()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->show(9);
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }
}
