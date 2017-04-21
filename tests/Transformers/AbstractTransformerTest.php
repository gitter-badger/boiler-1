<?php

namespace Yakuzan\Boiler\Tests\Transformers;

use function fractal;
use Laracasts\TestDummy\Factory;
use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;
use Yakuzan\Boiler\Tests\Stubs\Transformers\LessonTransformer;
use Yakuzan\Boiler\Tests\TestCase;

class AbstractTransformerTest extends TestCase
{
    /** @var Lesson $lesson */
    protected $lesson;

    protected function setUp()
    {
        parent::setUp();

        $this->lesson = Factory::create(Lesson::class);
    }

    /** @test */
    public function it_bind_entity_attribut()
    {
        $lesson = fractal($this->lesson, new LessonTransformer())->toArray();
        $this->assertEquals([
            'data' => [
                'id'      => $this->lesson->id,
                'titre'   => $this->lesson->title,
                'matiere' => $this->lesson->subject,
            ],
        ], $lesson);
    }
}
