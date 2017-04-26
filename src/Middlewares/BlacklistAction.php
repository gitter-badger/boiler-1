<?php

namespace Yakuzan\Boiler\Middlewares;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Yakuzan\Boiler\Controllers\AbstractController;
use Yakuzan\Boiler\Traits\ResponseTrait;

class BlacklistAction
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

        if (!empty($request->route()) && is_a($request->route()->getController(), AbstractController::class) && in_array($request->route()->getActionMethod(), $request->route()->getController()->blacklist(), true)) {
            if ($request->wantsJson()) {
                return $this->unauthorized();
            }

            throw new AuthorizationException();
        }

        return $response;
    }
}
