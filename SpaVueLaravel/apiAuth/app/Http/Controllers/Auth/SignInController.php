<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class SignInController extends Controller
{
    public function __invoke(Request $request)
    {
    	if( !$token = Auth::attempt( $request->only('email','password') ) )
    	{
    		return response()->json(null, 401);
    	}

    	return response()->json( compact('token') );
    }
    
}
