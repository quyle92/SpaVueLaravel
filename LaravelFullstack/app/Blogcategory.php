<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blogcategory extends Model
{
    protected $fillable = ['id','category_id', 'blog_id'];
}
