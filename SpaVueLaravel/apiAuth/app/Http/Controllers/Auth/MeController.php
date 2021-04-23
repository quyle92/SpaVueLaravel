<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:api', ['except' => ['login']]);
    }

    public function __invoke(Request $request)
    {
    	///$user = $request->user();(1a)
        $user = Auth::guard('api')->user();//(1b)
    	return response()->json([
    		'email' => $user->email,
    		'name' => $user->name
    	]);
    }
}

/**Note**/
//(1a) cách 1
//(1a) cách 2: https://mattstauffer.com/blog/multiple-authentication-guard-drivers-including-api-in-laravel-5-2/
