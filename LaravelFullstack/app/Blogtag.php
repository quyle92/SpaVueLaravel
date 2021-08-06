<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blogtag extends Model
{
    protected $fillable = ['id','tag_id', 'blog_id'];
}
