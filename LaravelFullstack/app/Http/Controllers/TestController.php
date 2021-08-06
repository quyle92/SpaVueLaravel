<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class TestController extends Controller
{
    public function controllerMethod()
    {
    	return response()->json([
    		'msg' => 'we should return only json'
    	]);
    }

    public function test()
    {
    	return response()->json([
    		  [
			    "id"=> 1,
			    "title"=> "Post 1"
			  ],
			  [
			    "id"=>2,
			    "title"=> "Post 2"
			  ],
			  [
			    "id"=> 3,
			    "title" => "Post 3"
			  ]
    	]);
    }
}
