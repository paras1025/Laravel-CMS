<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // A category has many posts
    public function posts()
    {
    	return $this->hasMany(Post::class);
    }
}
