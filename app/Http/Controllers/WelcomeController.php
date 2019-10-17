<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Post;
use App\Tag;

class WelcomeController extends Controller
{
    public function index()
    {
        
        return view('welcome')
                ->with('categories', Category::all())
                // We are using pagination here
                ->with('posts', Post::searched()->simplePaginate(1))
                ->with('tags', Tag::all());
    }
}
