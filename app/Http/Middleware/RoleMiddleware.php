<?php

namespace Donatella\Http\Middleware;

use Closure;
use Donatella\Models\RoleWeb;
use Donatella\User;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user_role = User::where('id',Auth::user()->id)->get()->load('role');
        foreach ($roles as $role){
            if ($user_role[0]->role->tipo_role == $role){
                return $next($request);
            }
        }
        return view ('partials.errors.role');
    }
}
