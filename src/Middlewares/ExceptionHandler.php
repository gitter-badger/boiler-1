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
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $route = $request->route();

        if (($controller = $route->controller) instanceof \Yakuzan\Boiler\Controllers\AbstractController
            && in_array($route->getActionMethod(), $controller->blacklist(), true)) {

            if ($request->wantsJson()) {
                return $this->unauthorized();
            }

            throw new \Illuminate\Auth\Access\AuthorizationException();
        }

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
