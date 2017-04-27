<?php

namespace Yakuzan\Boiler\Tests\Controllers;

use Laracasts\TestDummy\Factory;
use Yakuzan\Boiler\Controllers\AbstractApiController;
use Yakuzan\Boiler\Requests\BoilerRequest;
use Yakuzan\Boiler\Tests\Stubs\Controllers\LessonApiController;
use Yakuzan\Boiler\Tests\Stubs\Entities\Lesson;
use Yakuzan\Boiler\Tests\Stubs\Policies\LessonPolicy;
use Yakuzan\Boiler\Tests\Stubs\Services\LessonService;
use Yakuzan\Boiler\Tests\TestCase;

class AbstractApiControllerTest extends TestCase
{
    /** @var AbstractApiController $controller */
    protected $controller;

    public function setUp()
    {
        parent::setUp();

        $this->controller = new LessonApiController();

        $this->app['router']->get('/lesson', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonApiController@index');
        $this->app['router']->get('/lesson/{lesson}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonApiController@show');
        $this->app['router']->post('/lesson', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonApiController@store');
        $this->app['router']->put('/lesson/{lesson}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonApiController@update');
        $this->app['router']->delete('/lesson/{lesson}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonApiController@destroy');

        $this->app['router']->get('/lessonPolicy', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonPolicyApiController@index');
        $this->app['router']->get('/lessonPolicy/{lessonPolicy}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonPolicyApiController@show');
        $this->app['router']->post('/lessonPolicy', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonPolicyApiController@store');
        $this->app['router']->put('/lessonPolicy/{lessonPolicy}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonPolicyApiController@update');
        $this->app['router']->delete('/lessonPolicy/{lessonPolicy}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonPolicyApiController@destroy');

        $this->app['router']->get('/lessonBlacklist', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonBlacklistApiController@index');
        $this->app['router']->get('/lessonBlacklist/{lessonBlacklist}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonBlacklistApiController@show');
        $this->app['router']->post('/lessonBlacklist', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonBlacklistApiController@store');
        $this->app['router']->put('/lessonBlacklist/{lessonBlacklist}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonBlacklistApiController@update');
        $this->app['router']->delete('/lessonBlacklist/{lessonBlacklist}', 'Yakuzan\Boiler\Tests\Stubs\Controllers\LessonBlacklistApiController@destroy');
    }

    /** @test */
    public function it_return_pagination_with_api_response()
    {
        $lessons = Factory::times(10)->create(Lesson::class)->all();

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lesson');

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertCount($result->getData(true)['meta']['pagination']['total'], $lessons);
    }

    /** @test */
    public function it_return_response_not_found_when_lessons_table_empty()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lesson');
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_return_with_id()
    {
        $lessons = Factory::times(10)->create(Lesson::class)->all();
        $lesson = next($lessons);
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lesson/'.$lesson->id);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($lesson->id, $result->getData(true)['data']['id']);
    }

    /** @test */
    public function it_return_response_not_found_when_lesson_not_found()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lesson/9');
        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_create_a_new_record_in_lessons_table()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/lesson', ['title' => 'new title', 'subject' => 'new subject']);
        $this->assertEquals(201, $result->getStatusCode());
        $this->assertArrayHasKey('id', $result->getData(true)['response']['data']);
    }

    /** @test */
    public function it_return_validation_errors_when_creating_a_new_record()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/lesson', ['subject' => 'new subject']);

        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals('The given data failed to pass validation.', $result->getData(true)['error']['message']);
        $this->assertEquals('The title field is required.', $result->getData(true)['error']['data']['title'][0]);
    }

    /** @test */
    public function it_update_an_existing_lesson_in_database()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/lesson/'.$lesson->id, ['title' => 'new title', 'subject' => 'new subject']);
        $this->assertEquals(202, $result->getStatusCode());
        $this->assertEquals('Accepted', $result->getData(true)['response']['message']);
    }

    /** @test */
    public function it_return_validation_errors_when_updating_a_lesson()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/lesson/'.$lesson->id, ['subject' => 'new subject']);

        $this->assertEquals(422, $result->getStatusCode());
        $this->assertEquals('The given data failed to pass validation.', $result->getData(true)['error']['message']);
        $this->assertEquals('The title field is required.', $result->getData(true)['error']['data']['title'][0]);
    }

    /** @test */
    public function it_delete_a_record_from_the_database()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('DELETE', '/lesson/'.$lesson->id);

        $this->assertEquals(204, $result->getStatusCode());
        $this->assertEquals('No Content', $result->getData(true)['response']['message']);
    }

    /** @test */
    public function it_return_error_when_trying_to_delete_lesson_that_does_not_exist()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('DELETE', '/lesson/9');

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_return_error_when_trying_to_update_lesson_that_does_not_exist()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/lesson/9', ['title' => 'new title', 'subject' => 'new subject']);

        $this->assertEquals(404, $result->getStatusCode());
        $this->assertEquals('Not Found', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_authorize_admin_and_user_to_list_lessons()
    {
        Factory::create(Lesson::class);

        $service = new LessonService();
        $service->policy(LessonPolicy::class);

        auth()->login($this->younes);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->service($service)->index(new BoilerRequest());

        $this->assertEquals(200, $result->getStatusCode());

        auth()->login($this->imane);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->service($service)->index(new BoilerRequest());

        $this->assertEquals(200, $result->getStatusCode());
    }

    /** @test */
    public function it_authorize_admin_and_user_to_list_lessons_policy()
    {
        Factory::create(Lesson::class);

        auth()->login($this->younes);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lessonPolicy');

        $this->assertEquals(200, $result->getStatusCode());

        auth()->login($this->imane);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lessonPolicy');

        $this->assertEquals(200, $result->getStatusCode());
    }

    /** @test */
    public function it_authorize_admin_and_user_to_show_a_lesson()
    {
        $lesson = Factory::create(Lesson::class);

        $service = new LessonService();
        $service->policy(LessonPolicy::class);

        auth()->login($this->younes);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->service($service)->show(new BoilerRequest(), $lesson->id);

        $this->assertEquals(200, $result->getStatusCode());

        auth()->login($this->imane);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->service($service)->show(new BoilerRequest(), $lesson->id);

        $this->assertEquals(200, $result->getStatusCode());
    }

    /** @test */
    public function it_authorize_admin_and_user_to_show_a_lesson_policy()
    {
        $lesson = Factory::create(Lesson::class);

        auth()->login($this->younes);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lessonPolicy/'.$lesson->id);

        $this->assertEquals(200, $result->getStatusCode());

        auth()->login($this->imane);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lessonPolicy/'.$lesson->id);

        $this->assertEquals(200, $result->getStatusCode());
    }

    /** @test */
    public function it_authorize_admin_to_create_a_new_lesson()
    {
        $service = new LessonService();
        $service->policy(LessonPolicy::class);

        auth()->login($this->younes);
        $request = new BoilerRequest();
        $request->merge(['title' => 'new title', 'subject' => 'new subject']);
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->service($service)->store($request);

        $this->assertEquals(201, $result->getStatusCode());
    }

    /** @test */
    public function it_authorize_admin_to_create_a_new_lesson_policy()
    {
        auth()->login($this->younes);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/lessonPolicy', ['title' => 'new title', 'subject' => 'new subject']);

        $this->assertEquals(201, $result->getStatusCode());
    }

    /**
     * @test
     * @expectedException \Illuminate\Auth\Access\AuthorizationException
     * @expectedExceptionMessage This action is unauthorized.
     */
    public function it_deny_user_to_create_a_new_lesson()
    {
        $service = new LessonService();
        $service->policy(LessonPolicy::class);

        auth()->login($this->imane);

        $request = new BoilerRequest();
        $request->merge(['title' => 'new title', 'subject' => 'new subject']);

        $this->controller->service($service)->store($request);
    }

    /**
     * @test
     */
    public function it_deny_user_to_create_a_new_lesson_policy()
    {
        auth()->login($this->imane);
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/lessonPolicy', ['title' => 'new title', 'subject' => 'new subject']);

        $this->assertEquals(401, $result->getStatusCode());
        $this->assertEquals('Unauthorized', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_authorize_admin_to_update_a_lesson()
    {
        $service = new LessonService();
        $service->policy(LessonPolicy::class);

        auth()->login($this->younes);

        $lesson = Factory::create(Lesson::class);

        $request = new BoilerRequest();
        $request->merge(['title' => 'new title', 'subject' => 'new subject']);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->service($service)->update($request, $lesson->id);
        $this->assertEquals(202, $result->getStatusCode());
    }

    /** @test */
    public function it_authorize_admin_to_update_a_lesson_policy()
    {
        auth()->login($this->younes);

        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/lessonPolicy/'.$lesson->id, ['title' => 'new title', 'subject' => 'new subject']);
        $this->assertEquals(202, $result->getStatusCode());
    }

    /**
     * @test
     * @expectedException \Illuminate\Auth\Access\AuthorizationException
     * @expectedExceptionMessage This action is unauthorized.
     */
    public function it_deny_user_to_update_a_lesson()
    {
        $service = new LessonService();
        $service->policy(LessonPolicy::class);

        auth()->login($this->imane);

        $lesson = Factory::create(Lesson::class);

        $request = new BoilerRequest();
        $request->merge(['title' => 'new title', 'subject' => 'new subject']);

        $this->controller->service($service)->update($request, $lesson->id);
    }

    /**
     * @test
     */
    public function it_deny_user_to_update_a_lesson_policy()
    {
        auth()->login($this->imane);

        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('PUT', '/lessonPolicy/'.$lesson->id, ['title' => 'new title', 'subject' => 'new subject']);

        $this->assertEquals(401, $result->getStatusCode());
        $this->assertEquals('Unauthorized', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_authorize_admin_to_delete_a_lesson()
    {
        $service = new LessonService();
        $service->policy(LessonPolicy::class);

        auth()->login($this->younes);

        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->controller->service($service)->destroy(new BoilerRequest(), $lesson->id);

        $this->assertEquals(204, $result->getStatusCode());
    }

    /** @test */
    public function it_authorize_admin_to_delete_a_lesson_policy()
    {
        auth()->login($this->younes);

        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('DELETE', '/lessonPolicy/'.$lesson->id);

        $this->assertEquals(204, $result->getStatusCode());
    }

    /**
     * @test
     * @expectedException \Illuminate\Auth\Access\AuthorizationException
     * @expectedExceptionMessage This action is unauthorized.
     */
    public function it_deny_user_to_delete_a_lesson()
    {
        $service = new LessonService();
        $service->policy(LessonPolicy::class);

        auth()->login($this->imane);

        $lesson = Factory::create(Lesson::class);

        $this->controller->service($service)->destroy(new BoilerRequest(), $lesson->id);
    }

    /**
     * @test
     */
    public function it_deny_user_to_delete_a_lesson_policy()
    {
        auth()->login($this->imane);

        $lesson = Factory::create(Lesson::class);

        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('DELETE', '/lessonPolicy/'.$lesson->id);

        $this->assertEquals(401, $result->getStatusCode());
        $this->assertEquals('Unauthorized', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_deny_executing_blacklisted_functions()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('GET', '/lessonBlacklist');

        $this->assertEquals(401, $result->getStatusCode());
        $this->assertEquals('Unauthorized', $result->getData(true)['error']['message']);
    }

    /** @test */
    public function it_allow_others_function_not_in_black_list_to_pass()
    {
        /** @var \Illuminate\Http\JsonResponse $result */
        $result = $this->json('POST', '/lessonBlacklist', ['title' => 'new title', 'subject' => 'new subject']);

        $this->assertEquals(201, $result->getStatusCode());
    }
}
