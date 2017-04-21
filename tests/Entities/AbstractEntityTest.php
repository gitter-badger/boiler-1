<?php

namespace Yakuzan\Boiler\Tests\Entities;

use Illuminate\Http\Request;
use Laracasts\TestDummy\Factory;
use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;
use Yakuzan\Boiler\Tests\TestCase;

class AbstractEntityTest extends TestCase
{
    /** @var Lesson $lesson */
    protected $lesson;

    protected function setUp()
    {
        parent::setUp();

        $this->lesson = Factory::create(Lesson::class);
    }

    /** @test */
    public function it_return_access_rules_if_empty_modify_rule()
    {
        $this->lesson->set_access_rules(['title' => 'required']);
        $this->assertEquals(['title' => 'required'], $this->lesson->modify_rules(new Request()));
    }

    /** @test */
    public function it_return_fillable_attributes_if_access_attribut_are_not_set()
    {
        $this->assertEquals($this->lesson->getFillable(), $this->lesson->access_attributes());
    }

    /** @test */
    public function it_return_fillable_attributes_if_empty_modify_attribut()
    {
        $this->assertEquals($this->lesson->getFillable(), $this->lesson->modify_attributes());
    }

    /** @test */
    public function it_set_and_get_access_attribut()
    {
        $this->lesson->access_attributes(['id', 'title', 'created_at', 'updated_at']);
        $this->assertEquals(['id', 'title', 'created_at', 'updated_at'], $this->lesson->access_attributes());
    }

    /** @test */
    public function it_set_and_get_modify_attribut()
    {
        $this->lesson->modify_attributes(['id', 'title', 'created_at', 'updated_at']);
        $this->assertEquals(['id', 'title', 'created_at', 'updated_at'], $this->lesson->modify_attributes());
    }

    /** @test */
    public function it_return_access_attributes_if_modify_are_not_set()
    {
        $this->lesson->access_attributes(['id', 'title', 'created_at', 'updated_at']);
        $this->assertEquals(['id', 'title', 'created_at', 'updated_at'], $this->lesson->modify_attributes());
    }

}
