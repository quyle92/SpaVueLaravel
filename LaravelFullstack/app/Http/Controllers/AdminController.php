<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Blog;
use App\Role;
use App\User;
use Exception;
use App\Blogtag;
use App\Category;
use App\Blogcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Validator;

class AdminController extends Controller
{   
    public function index(Request $request)
    {   
        // dd(User::find( Auth::user()->id )->role->roleName);//(2)
        
        if($request->path() =='logout'){
            $this->logout();
        }
        //dd( $request->path() );
        //redirect to login page if you're not logged in  or url other than "login"
        // if( ! Auth::check() && $request->path() !='login'  || Auth::check() && Auth::user()->role->isAdmin !== 1 )
        // {  
        //     return redirect('/login');
        // }
        // elseif( Auth::check() && $request->path() =='login')
        // { 
        //     return redirect('/');
        // }

        // if( $request->path() !== 'login')
        // {
        //     $isAllowed = $this->checkPermission( $request );
        //     if( $isAllowed === false)
        //     {
        //         return 'not found';
        //     }
        // }
        return view('welcome');

    }

    public function checkPermission( $request )
    { 
        $isAllowed = false;
        $permissions = json_decode( Auth::user()->role->permission );  
        foreach($permissions as  $pms )
        {
            if ( $request->path() === $pms->path && $pms->read === true)
                $isAllowed = true;
        }
        return $isAllowed;
    }

    public function loginProcess(Request $request)
    { 
        request()->validate([
            'fullName' => 'required',
            'password' => 'required|bail|min:3',
        ]);
        $credentials = $request->only('fullName', 'password');
        
        if( Auth::attempt( $credentials ) )
        {   
        // dd( Auth::user()->role->isAdmin);
            if( Auth::user()->role->isAdmin !== 1 ){
                Auth::logout();
                return response([
                    'msg' =>  'Sorry! admin only...'
                ], 401);
            } 
            // var_dump(Auth::check());die;
            return response([
                'user' =>  Auth::user()
            ], 200);
        }
        else
        {
            return response([
                'msg' =>  'login failed'
            ], 401);
        }
        
    }

    public function logout(){var_dump(Auth::check());
        //$response->header('X-Header-logout', 'This was created in logout.');
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request)
    {   
        $this->validate($request, [
            'tagName' => 'required|unique:tags',
        ]);

    	$tag = Tag::create([
    		'tagName' => $request->tagName
    	]);

    	return response([
            'tag' => $tag
        ], 200);
    }

    public function get_tags(Request $request)
    {   
        if($request->foo)
        {
            return Tag::orderBy('created_at', 'asc')->get();
        }
    	return Tag::orderBy('created_at', 'asc')->paginate(2);
    }

    public function editTag(Request $request)
    {   
        $this->validate($request, [
            'tagName' => 'required|unique:tags',
            'id' => 'required',
        ]);

        $tag = Tag::findOrFail($request->id);

        $tag->tagName = $request->input('tagName');
        
        $tag->save();

        return response([
            'tag' => $tag
        ], 200);
    }

    public function delTag(Request $request)
    {  
        $tag = Tag::findOrFail($request->item_id);
        $tag->delete();

        // xóa xong rồi thì sẽ trở lại đúng vị trí đc paginated đó
        $current_page = $request->current_page;//(4)
        Paginator::currentPageResolver(function() use($current_page) {
            return $current_page;
        });
        $tag = Tag::paginate(2);
        // nếu vị trí paginated đó sau khi delete item cuối cùng thì lùi $current_page lại 1 page
        if( empty( $tag[0] ) && $current_page !== 1 )
        {
            Paginator::currentPageResolver(function() use($current_page) {
                return $current_page - 1;
            });
            $tag = Tag::paginate(2);
        }

        return $tag;
    }

    public function getCat()
    {
        return Category::orderBy('created_at', 'desc')->get();
    }

    public function addCat(Request $request)
    {  
        request()->validate([
            'iconImage' => 'required|mimes:jpeg,png,jpg,gif,svg|max:9048',
            'categoryName' => 'required|unique:categories,categoryName',
        ]);

        $picName = $request->iconImage->getClientOriginalName() . '.' . $request->iconImage->extension();
        $request->iconImage->move( public_path('uploads'), $picName ); 
        
        $categoryName = $request->categoryName;
        $cat = Category::create([
            'iconImage' => $picName ,
            'categoryName' => $categoryName
        ]);

        return response([
            'cat' => $cat
        ], 200);
    }

    public function editCat(Request $request)
    {      
        // dd($request->id);
        $id = Category::findOrFail($request->id);
        // nếu file ko thay đổi thì validate categoryName thôi
        if( is_object( $request->iconImage ) )
        {   
            request()->validate([
            'iconImage' => 'required|mimes:jpeg,png,jpg,gif,svg|max:9048',
            'categoryName' => 'required', Rule::unique('categories, categoryName')->ignore($id),
            ]);

            $picName = $request->iconImage->getClientOriginalName() . '.' . $request->iconImage->extension();
            $request->iconImage->move( public_path('uploads'), $picName ); 
        } 
        else
        {
            request()->validate([
                'categoryName' => 'required', Rule::unique('categories, categoryName')->ignore($id),
            ]);

            $picName = $request->iconImage;
        } 
        

        $cat = Category::findOrFail($request->id);

        $cat->iconImage = $picName;
        $cat->categoryName = $request->input('categoryName');
        
        $cat->save();

        return response([
            'cat' => $cat
        ], 200);
    }

    public function delCat(Request $request)
    {   
        $file_path = "uploads/".$request->iconImage;
        if(file_exists($file_path)){
            unlink($file_path);
        }
        $cat = Category::findOrFail($request->id);
        $cat->delete();
        
        return Category::orderBy('id', 'asc')->get();
    }

    public function addUser(Request $request)
    {   
        request()->validate([
            'fullName' => 'required',
            'email' => 'required|bail|email',
            'password' => 'required|bail|min:3',
            'role_id' => 'required',
        ]);

        $password = bcrypt($request->password);
        
        $user = User::create([
            'fullName' => $request->fullName ,
            'email' => $request->email ,
            'password' => $password ,
            'role_id' => $request->role_id ,

        ]);

        return response([
            'user' => $user
        ], 200);
    }

    public function editUser(Request $request)
    {      
        request()->validate([
            'fullName' => 'required',
            'email' => 'required|bail|email',
            //'password' => 'required|bail|min:3',
            'role_id' => 'required',
        ]);

        //$password = bcrypt($request->password);
        

        $user = User::findOrFail($request->id);

        $user->fullName = $request->input('fullName');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        
        $user->save();

        return response([
            'user' => $user
        ], 200);
    }

    public function getUser(Request $request)
    {   
        return User::orderBy('created_at', 'asc')->get();
    }

    public function delUser(Request $request)
    {   
        $tag = User::findOrFail($request->id);
        $tag->delete();
        
        return User::orderBy('created_at', 'desc')->get();
    }

    

    public function addRole(Request $request)
    {   //dd($request);
        request()->validate([
            'roleName' => 'required',
        ]);
        
        $role = Role::create([
            'roleName' => $request->roleName ,

        ]);

        return response([
            'role' => $role
        ], 200);
    }

    public function editRole(Request $request)
    {      
        request()->validate([
            'roleName' => 'required',

        ]);
        

        $role = Role::findOrFail($request->id);

        $role->roleName = $request->input('roleName');

        $role->save();

        return response([
            'role' => $role
        ], 200);
    }

    public function getRole(Request $request)
    {   
        return Role::orderBy('created_at', 'asc')->get();
    }

    public function delRole(Request $request)
    {   
        $role = Role::findOrFail($request->id);
        $role->delete();
        
        return Role::orderBy('created_at', 'desc')->get();
    }

    public function assignRole(Request $request)
    {      
        request()->validate([
            'role_id' => 'required',
            'permission' => 'required',
        ]);
        
        
        $role = Role::findOrFail($request->role_id);

        $role->permission = $request->input('permission');

        $role->save();

        return response([
            'role' => $role
        ], 200);
    }

    public function getRoleAssigned(Request $request)
    {   
        $roles = Role::orderBy('created_at', 'asc')->get();

        return $subset = $roles->map(function ($role) {
            return collect($role->toArray())
            ->only(['id', 'permission'])
            ->all();
        });
        //var_dump($subset);die;
    }

    public function addBlog(Request $request)
    {   
        DB::beginTransaction();
        try
        {
            $blog = Blog::create($request->all());

            $categories = $request->category_id;
            $blogCat = [];
            foreach($categories  as $cat){
                $blogCat[] = [
                    'category_id' =>  $cat,
                    'blog_id' =>  $blog->id,
                ];
            }
            DB::table('blogcategories')->insert($blogCat);//(3)

            $tags = $request->tag_id;
            $blogTag = [];
            foreach($tags as $tag){
                array_push($blogTag, [
                    'tag_id' =>  $tag,
                    'blog_id' =>  $blog->id,
                ]);
            }
            DB::table('blogtags')->insert($blogTag);
            DB::commit();
            
            return response([
                'blog' => $blog,
            ], 200);

            
        }
        catch(Exception $e)
        {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
        
    }
    
    public function getBlog(Request $request)
    {    
        return Blog::with(['tags','categories'])->orderBy('created_at', 'asc')->get();
    }

    public function delBlog(Request $request)
    {     
        $blog = Blog::findOrFail($request->id);

        $blog->delete();
        
        return Blog::orderBy('created_at', 'desc')->get();
    }
}

/**Note**/
//(1)phải ghi liền, ko có khoảng trắng, ko Laravel sẽ validate sai
//(2) this is  to  retrieve records in Eloquent relationship.
//cách 2: dd( Auth::user()->role->roleName );
//Ref: https://laravel.com/docs/5.8/eloquent-relationships#one-to-one
//(3) must use "insert" instead of "create" for creating more than 1 record in the DB, else fail to create.
////(4): get current paginated page https://stackoverflow.com/a/31847286/11297747