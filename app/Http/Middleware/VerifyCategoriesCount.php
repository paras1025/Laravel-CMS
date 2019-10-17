<?php

namespace App\Http\Middleware;

use Closure;    
use App\Category;

class VerifyCategoriesCount
{
    /**
     * Handle an incoming request.
     * Checks is there is no category, create a category before creating a post.
     * Redirect the user to create at least one category.
     * Register this middleware in Kernel.php - this is the controller for all the incoming requests.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Category::all()->count() == 0) 
        {
            session()->flash('error', 'You need to add category to be able to create a post.');
            return redirect(route('categories.create'));
        }
        return $next($request);
    }
}
