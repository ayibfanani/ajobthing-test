<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$type)
    {
        $user = auth()->user();
        $role = $user->role;

        abort_if(!in_array($role['key'], $type), 403);

        return $next($request);
    }
}
