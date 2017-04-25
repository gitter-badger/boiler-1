<?php

namespace Yakuzan\Boiler\Middlewares;

use Closure;
use Yakuzan\Boiler\Traits\ResponseTrait;

class ExceptionHandler
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!empty($response->exception) && $request->expectsJson()) {
            if ($response->exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return $this->unauthorized();
            }

            if ($response->exception instanceof  \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return $this->notFound();
            }

            if ($response->exception instanceof \Illuminate\Auth\AuthenticationException) {
                return $this->unauthorized('Unauthenticated');
            }
        }

        return $response;
    }
}
