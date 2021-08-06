<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminCheck
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
        if ( ! Auth::check() ) {
            return response()->json([
                'msg' => 'you\'re not allowed to access this route!'
            ], 403);
        }

        if ( Auth::user()->role->isAdmin !== 1 ) 
        {
            return response()->json([
                'msg' => 'you\'re  just user!'
            ], 403);
        }

        // return $next($request);
        $uri = Route::getCurrentRoute()->uri;//"app/tags/update"
        $resource_uri = explode("/",$uri)[1]; 
        $resource_action = explode("/",$uri)[2];

        if($resource_action !=='get' && $resource_action !=='assignRole' && $resource_action !=='getRoleAssigned')
        {
            $permissions = json_decode( Auth::user()->role->permission);//dd($permissions);
            foreach($permissions as $pms)
            {
                if($pms->resourceName === $resource_uri && $pms->$resource_action === true)
                {
                    return $next($request);
                }
                elseif ($pms->resourceName === $resource_uri && $pms->$resource_action === false) {
                    return response([
                        'errors' =>  'you\'re not allowed to ' . $resource_action . ' this resource'
                    ], 403);
                }
            }
        }
        return $next($request);
    }
}
