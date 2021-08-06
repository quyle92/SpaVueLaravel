<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['id', 'categoryName', 'iconImage','categorySlug'];

    public function getCategoryNameAttribute($value)
    {
    	return strtoupper($value);
    }

    public function setCategoryNameAttribute($categoryName)
    {
    	 $this->attributes['categorySlug'] = $this->uniqueSlug($categoryName);
    	 $this->attributes['categoryName'] = $categoryName;
    }


    public function uniqueSlug($categoryName)
    {
    	$slug = Str::slug( $categoryName, '-' );
    	$count = Category::where('categorySlug', 'LIKE', "{$slug}%")->count();
    	$newCount = $count > 0 ? $count++ : '';
    	return $newCount > 0 ? $slug . '-' . $newCount : $slug;
    }


}
