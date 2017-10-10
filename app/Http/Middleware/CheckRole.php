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
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $actions = $request->route()->getAction();
        $roles   = isset($actions['roles']) ? $actions['roles'] : null;
        
        if($request->user()->hasRole($roles) || !$roles){
            return $next($request);
        }

        return redirect('');
    }
}
