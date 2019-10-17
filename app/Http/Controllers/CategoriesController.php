<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Http\Requests\Categories\CreateCategoryRequest;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * Returns a view categories.index with all the categories in DB
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('categories.index')->with('categories', Category::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        
        // Creates a catefory with the requested input name
        Category::create([
            'name' => $request->name
            ]);

        // Flashed a success message which is handled in layouts/app.blade.php using the key
        session()->flash('success', 'Category created successfully.');

        return redirect(route('categories.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

  

    /**
     * Update the specified resource in storage.
     * We have used route binding here.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // $category->name = $request->name;
        // $category->save();

        //OR

        $category->update([
            'name' => $request->name
            ]);

        session()->flash('success', 'Category updated successfully.');
        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     * We have used route binding here.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        // To delete category along with all the related posts
        // $category->posts()->forceDelete();

        // Check if this category has any post
        if($category->posts->count() > 0)
        {
            session()->flash('error', 'Category can not be deleted because it has ' . $category->posts->count() . ' linked post(s).');

            return redirect()->back();
        }

        // To delete only cateory
        $category->delete();

        // dd(Post::all()->where('category_id', $category->id));
        
        session()->flash('success', 'Category deleted successfully along with the linked posts.');
        return redirect(route('categories.index'));
    }
}
