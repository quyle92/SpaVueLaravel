<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SignOutController extends Controller
{
    public function __invoke()
    {	
    	//no();
    	auth()->logout();
    	return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
    }
}
