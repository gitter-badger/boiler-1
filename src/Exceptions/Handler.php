<?php

namespace Yakuzan\Boiler\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Yakuzan\Boiler\Traits\ResponseTrait;

class Handler extends \App\Exceptions\Handler
{
    use ResponseTrait;

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Exception $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return $this->unauthorized();
            }
            if($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException){
                return $this->notFound();
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return $this->unauthorized('Unauthenticated');
        }

        return redirect()->guest(route('login'));
    }
}
