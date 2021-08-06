<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserRoleManagement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        //dd(Auth::user());
        if(Auth::user()->role->roleName !== 'admin')
        {
            return response()->json([
                'msg' => 'this is only for super admin!'
            ], 403);
        }
        return $next($request);
    }
}
