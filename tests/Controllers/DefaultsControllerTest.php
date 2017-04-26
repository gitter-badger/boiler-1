<?php

namespace Yakuzan\Boiler\Tests\Controllers;

use Laracasts\TestDummy\Factory;
use Yakuzan\Boiler\Controllers\AbstractApiController;
use Yakuzan\Boiler\Services\DefaultService;
use Yakuzan\Boiler\Tests\Stubs\Controllers\DefaultsController;
use Yakuzan\Boiler\Tests\Stubs\Entities\Defaults;
use Yakuzan\Boiler\Tests\TestCase;
use Yakuzan\Boiler\Transformers\DefaultTransformer;

class DefaultsControllerTest extends TestCase
{
    /** @var AbstractApiController */
    protected $controller;

    public function setUp()
    {
        parent::setUp();

        $this->controller = new DefaultsController();

        $this->app['router']->get('/defaults', 'Yakuzan\Boiler\Tests\Stubs\Controllers\DefaultsController@index');
        $this->app['router']->get('/defaults/{defaults}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\DefaultsController@show');
        $this->app['router']->post('/defaults', 'Yakuzan\Boiler\Tests\Stubs\Controllers\DefaultsController@store');
        $this->app['router']->put('/defaults/{defaults}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\DefaultsController@update');
        $this->app['router']->delete('/defaults/{defaults}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\DefaultsController@destroy');
    }

    /** @test */
    public function it_defaults_service()
    {
        $service = $this->controller->service();
        $this->assertInstanceOf(DefaultService::class, $service);
    }

    /** @test */
    public function it_defaults_transformer()
    {
        $transformer = $this->controller->transformer();
        $this->assertInstanceOf(DefaultTransformer::class, $transformer);
    }

    /** @test */
    public function it_return_pagination_with_api_response()
    {
        $lessons = Factory::times(10)->create(Defaults::class)->all();

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/defaults');

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertCount($result->getData(true)['meta']['pagination']['total'], $lessons);
    }

    /** @test */
    public function it_return_response_not_found_when_defaults_table_empty()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/defaults');
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_return_with_id()
    {
        $defaultss = Factory::times(10)->create(Defaults::class)->all();
        $defaults = next($defaultss);
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/defaults/'.$defaults->id);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($defaults->id, $result->getData(true)['data']['id']);
    }

    /** @test */
    public function it_return_response_not_found_when_defaults_not_found()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/defaults/9');
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_create_a_new_record_in_defaultss_table()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/defaults', ['title' => 'new title']);
        $this->assertEquals(201, $result->getStatusCode());
        $this->assertArrayHasKey('id', $result->getData(true)['response']['data']);
    }

    /** @test */
    public function it_return_validation_errors_when_creating_a_new_record()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/defaults', []);

        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals('The given data failed to pass validation.', $result->getData(true)['error']['message']);
        $this->assertEquals('The title field is required.', $result->getData(true)['error']['data']['title'][0]);
    }

    /** @test */
    public function it_update_an_existing_defaults_in_database()
    {
        $defaults = Factory::create(Defaults::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/defaults/'.$defaults->id, ['title' => 'new title']);
        $this->assertEquals(202, $result->getStatusCode());
        $this->assertEquals('Accepted', $result->getData(true)['response']['message']);
    }

    /** @test */
    public function it_return_validation_errors_when_updating_a_defaults()
    {
        $defaults = Factory::create(Defaults::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/defaults/'.$defaults->id, []);

        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals('The given data failed to pass validation.', $result->getData(true)['error']['message']);
        $this->assertEquals('The title field is required.', $result->getData(true)['error']['data']['title'][0]);
    }

    /** @test */
    public function it_delete_a_record_from_the_database()
    {
        $defaults = Factory::create(Defaults::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('DELETE', '/defaults/'.$defaults->id);

        $this->assertEquals(204, $result->getStatusCode());
        $this->assertEquals('No Content', $result->getData(true)['response']['message']);
    }

    /** @test */
    public function it_return_error_when_trying_to_delete_defaults_that_does_not_exist()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('DELETE', '/defaults/9');

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_return_error_when_trying_to_update_defaults_that_does_not_exist()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/defaults/9', ['title' => 'new title']);

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }
}
