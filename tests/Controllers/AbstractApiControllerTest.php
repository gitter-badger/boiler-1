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

    /** @test */
    public function it_create_a_new_record_in_lessons_table()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')->andReturn(['title' => 'new title', 'subject' => 'new subject']);
        $request->shouldReceive('only')->with(['title', 'subject'])->andReturn(['title' => 'new title', 'subject' => 'new subject']);
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->store($request);
        $this->assertEquals(201, $result->getStatusCode());
        $this->assertArrayHasKey('id', $result->getData(true)['response']['data']);
    }

    /** @test */
    public function it_return_validation_errors_when_creating_a_new_record()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')->andReturn(['subject' => 'new subject']);
        $request->shouldReceive('expectsJson')->andReturn(true);
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->store($request);

        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals('The given data failed to pass validation.', $result->getData(true)['error']['message']);
        $this->assertEquals('The title field is required.', $result->getData(true)['error']['data']['title'][0]);
    }

    /** @test */
    public function it_update_an_existing_lesson_in_database()
    {
        $lesson = Factory::create(Lesson::class);

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')->andReturn(['title' => 'new title', 'subject' => 'new subject']);
        $request->shouldReceive('only')->with(['title', 'subject'])->andReturn(['title' => 'new title', 'subject' => 'new subject']);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->update($request, $lesson->id);
        $this->assertEquals(202, $result->getStatusCode());
        $this->assertEquals('Accepted', $result->getData(true)['response']['message']);
    }

    /** @test */
    public function it_return_validation_errors_when_updating_a_lesson()
    {
        $lesson = Factory::create(Lesson::class);

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')->andReturn(['subject' => 'new subject']);
        $request->shouldReceive('expectsJson')->andReturn(true);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->update($request, $lesson->id);

        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals('The given data failed to pass validation.', $result->getData(true)['error']['message']);
        $this->assertEquals('The title field is required.', $result->getData(true)['error']['data']['title'][0]);
    }

    /** @test */
    public function it_delete_a_record_from_the_database()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->destroy($lesson->id);

        $this->assertEquals(204, $result->getStatusCode());
        $this->assertEquals('No Content', $result->getData(true)['response']['message']);
    }

    /** @test */
    public function it_return_error_when_trying_to_delete_lesson_that_does_not_exist()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->destroy(1);

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_return_error_when_trying_to_update_lesson_that_does_not_exist()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')->andReturn(['title' => 'new title', 'subject' => 'new subject']);
        $request->shouldReceive('only')->with(['title', 'subject'])->andReturn(['title' => 'new title', 'subject' => 'new subject']);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->update($request, 1);

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }
}
