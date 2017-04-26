<?php

namespace Yakuzan\Boiler\Tests\Controllers;

use Laracasts\TestDummy\Factory;
use Yakuzan\Boiler\Controllers\AbstractApiController;
use Yakuzan\Boiler\Tests\Stubs\Controllers\GuessController;
use Yakuzan\Boiler\Tests\Stubs\Entities\Guess;
use Yakuzan\Boiler\Tests\Stubs\Policies\GuessPolicy;
use Yakuzan\Boiler\Tests\Stubs\Services\GuessService;
use Yakuzan\Boiler\Tests\Stubs\Transformers\GuessTransformer;
use Yakuzan\Boiler\Tests\TestCase;

class GuessControllerTest extends TestCase
{
    /** @var AbstractApiController */
    protected $controller;

    public function setUp()
    {
        parent::setUp();

        $this->controller = new GuessController();

        $this->app['router']->get('/guess', 'Yakuzan\Boiler\Tests\Stubs\Controllers\GuessController@index');
        $this->app['router']->get('/guess/{guess}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\GuessController@show');
        $this->app['router']->post('/guess', 'Yakuzan\Boiler\Tests\Stubs\Controllers\GuessController@store');
        $this->app['router']->put('/guess/{guess}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\GuessController@update');
        $this->app['router']->delete('/guess/{guess}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\GuessController@destroy');
    }

    /** @test */
    public function it_guess_service()
    {
        $service = $this->controller->service();
        $this->assertInstanceOf(GuessService::class, $service);
    }

    /** @test */
    public function it_guess_transformer()
    {
        $transformer = $this->controller->transformer();
        $this->assertInstanceOf(GuessTransformer::class, $transformer);
    }

    /** @test */
    public function it_guess_policy()
    {
        $policy = $this->controller->service()->policy();
        $this->assertInstanceOf(GuessPolicy::class, $policy);
    }

    /** @test */
    public function it_return_pagination_with_api_response()
    {
        $lessons = Factory::times(10)->create(Guess::class)->all();

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/guess');

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertCount($result->getData(true)['meta']['pagination']['total'], $lessons);
    }

    /** @test */
    public function it_return_response_not_found_when_guess_table_empty()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/guess');
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_return_with_id()
    {
        $guesss = Factory::times(10)->create(Guess::class)->all();
        $guess = next($guesss);
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/guess/'.$guess->id);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($guess->id, $result->getData(true)['data']['id']);
    }

    /** @test */
    public function it_return_response_not_found_when_guess_not_found()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/guess/9');
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_create_a_new_record_in_guesss_table()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/guess', ['title' => 'new title']);
        $this->assertEquals(201, $result->getStatusCode());
        $this->assertArrayHasKey('id', $result->getData(true)['response']['data']);
    }

    /** @test */
    public function it_return_validation_errors_when_creating_a_new_record()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/guess', []);

        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals('The given data failed to pass validation.', $result->getData(true)['error']['message']);
        $this->assertEquals('The title field is required.', $result->getData(true)['error']['data']['title'][0]);
    }

    /** @test */
    public function it_update_an_existing_guess_in_database()
    {
        $guess = Factory::create(Guess::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/guess/'.$guess->id, ['title' => 'new title']);
        $this->assertEquals(202, $result->getStatusCode());
        $this->assertEquals('Accepted', $result->getData(true)['response']['message']);
    }

    /** @test */
    public function it_return_validation_errors_when_updating_a_guess()
    {
        $guess = Factory::create(Guess::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/guess/'.$guess->id, []);

        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals('The given data failed to pass validation.', $result->getData(true)['error']['message']);
        $this->assertEquals('The title field is required.', $result->getData(true)['error']['data']['title'][0]);
    }

    /** @test */
    public function it_delete_a_record_from_the_database()
    {
        $guess = Factory::create(Guess::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('DELETE', '/guess/'.$guess->id);

        $this->assertEquals(204, $result->getStatusCode());
        $this->assertEquals('No Content', $result->getData(true)['response']['message']);
    }

    /** @test */
    public function it_return_error_when_trying_to_delete_guess_that_does_not_exist()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('DELETE', '/guess/9');

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_return_error_when_trying_to_update_guess_that_does_not_exist()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/guess/9', ['title' => 'new title']);

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }
}
