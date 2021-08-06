<?php

use App\Blog;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your lication. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'AdminController@index');
Route::get('{slug}', 'AdminController@index');
Route::get('/logout', 'AdminController@logout');
Route::post('/loginProcess', 'AdminController@loginProcess');

// Route::get('/blogPivotTable', function(){return (Blog::with('tags')->get());
// 			//retrieve blog instance with specific id
// 			return Blog::with('tags')->find(31);

// 			//retrieve all blog instance
// 			return Blog::with('tags')->get();

// 			//retrieve tags only of specific blog id
// 			$blog = Blog::find(32);
// 			foreach($blog->tags as $tag) {
// 			    // pivot attribute exists on related grado
// 			   echo($tag->tagName) . "\n";
// 			}
// });
/*
API only
 */
Route::middleware(['adminCheck'])->prefix('app')->group( function () {
	Route::group(['prefix' => 'tags'], function(){
		Route::get('/get', 'AdminController@get_tags');
		Route::post('/write', 'AdminController@addTag');
		Route::post('/update', 'AdminController@editTag');
		Route::post('/delete', 'AdminController@delTag');
	});

	Route::group(['prefix' => 'category'], function(){
		Route::get('/get', 'AdminController@getCat');
		Route::post('/write', 'AdminController@addCat');
		Route::post('/update', 'AdminController@editCat');
		Route::post('/delete', 'AdminController@delCat');
		Route::get('/slug', function(){
			$cat = App\Category::find(20);
			//dd($cat);
			return  $cat->categoryName;
		});
	});

	Route::group(['prefix' => 'blog'], function(){
		Route::get('/get', 'AdminController@getBlog');
		Route::post('/write', 'AdminController@addBlog');
		Route::post('/delete', 'AdminController@delBlog');

	});
	
	Route::middleware(['userRoleManagement'])->prefix('user')->group( function(){
		Route::get('/get', 'AdminController@getUser');
		Route::post('/write', 'AdminController@addUser');
		Route::post('/update', 'AdminController@editUser');
		Route::post('/delete', 'AdminController@delUser');
	});

	Route::middleware(['userRoleManagement'])->prefix('role')->group( function(){
		Route::get('/get', 'AdminController@getRole');
		Route::post('/write', 'AdminController@addRole');
		Route::post('/update', 'AdminController@editRole');
		Route::post('/delete', 'AdminController@delRole');
		Route::post('/assignRole', 'AdminController@assignRole');
		Route::get('/getRoleAssigned', 'AdminController@getRoleAssigned');
	});

});


