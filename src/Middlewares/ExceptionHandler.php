<?php

namespace Yakuzan\Boiler\Middlewares;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yakuzan\Boiler\Traits\ResponseTrait;

class ExceptionHandler
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws AuthorizationException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!empty($response->exception) && $request->expectsJson()) {
            switch (true) {
                case is_a($response->exception, AuthorizationException::class):
                    return $this->unauthorized();
                case is_a($response->exception, ModelNotFoundException::class):
                    return $this->notFound();
                case is_a($response->exception, AuthenticationException::class):
                    return $this->unauthorized('Unauthenticated');
            }
        }

        return $response;
    }
}
