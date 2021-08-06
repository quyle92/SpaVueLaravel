<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Blog extends Model
{
   protected $fillable = ['id','title', 'post', 'post_excerpt', 'slug', 'user_id', 'featuredImage', 'metaDescription', 'views', 'jsonData'];

    public function setPostAttribute($post){
        $this->attributes['post'] = $post;
        $this->attributes['post_excerpt'] = $post;

    }

    public function setTitleAttribute($title){
         $this->attributes['title'] = ($title);
         $this->attributes['slug'] = $this->uniqueSlug($title);
         $this->attributes['user_id'] = Auth::user()->id;
         $this->attributes['featuredImage'] = $this->uniqueSlug($title);
         $this->attributes['metaDescription'] = $this->uniqueSlug($title);
    }

    private function uniqueSlug($title){
        $slug = Str::slug($title, '-');
        $count = Blog::where('slug', 'LIKE', "{$slug}%")->count();
        $newCount = $count > 0 ? ++$count : '';
        return $newCount > 0 ? "$slug-$newCount" : $slug;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'blogtags', 'blog_id', 'tag_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'blogcategories', 'blog_id', 'category_id');
    }

}
