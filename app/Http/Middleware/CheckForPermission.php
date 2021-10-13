<?php

namespace App\Http\Middleware;

use App\Exceptions\MissingPermissionException;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class CheckForPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  mixed    ...$permissions
     *
     * @return mixed
     * @throws AuthenticationException
     * @throws MissingPermissionException
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (! $request->user() || ! $request->user()->tokens()) {
            throw new AuthenticationException();
        }

        foreach ($permissions as $permission) {
            if ($request->user()->tokenCan($permission)) {
                return $next($request);
            }
        }

        throw new MissingPermissionException($permissions);
    }
}
