<?php

namespace Yakuzan\Boiler\Tests\Services;

use Laracasts\TestDummy\Factory;
use Yakuzan\Boiler\Services\AbstractService;
use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;
use Yakuzan\Boiler\Tests\Stubs\Services\LessonService;
use Yakuzan\Boiler\Tests\TestCase;

class AbstractServiceTest extends TestCase
{
    /** @var AbstractService $service */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new LessonService();
    }

    /** @test */
    public function it_return_instance_of_lesson_entity()
    {
        $this->assertInstanceOf(Lesson::class, $this->service->entity());
    }

    /** @test */
    public function it_return_all_lessons()
    {
        $lessons = Factory::times(10)->create(Lesson::class);
        $result = $this->service->all();

        $this->assertEquals(count($lessons), count($result));
    }
}
