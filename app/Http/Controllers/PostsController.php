<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Post;
use App\Category;
use App\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PostsController extends Controller
{
    // Implemented a middleware for authentication on create and store posts 
    public function __construct()
    {
        $this->middleware('verifyCategoriesCount')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     * Returns a view posts.index with all the posts in DB
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index')->with('posts', Post::all());
    }

    /**
     * Show the form for creating a new resource.
     * Returns a view posts.create with all the categories in DB, this used to populate the category dropdown while creating a post
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with('categories', Category::all())->with('tags', Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        // Store image in local project folder
       
        $image = request()->file('image');
        $imageExtension = $image->getClientOriginalExtension();
        $imageFilename = $image->getFilename();
        
        Storage::disk('uploads')->put($imageFilename.'.'.$imageExtension, File::get($image));

        $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'image' => $imageFilename.'.'.$imageExtension,
                'category_id' => $request->category,
                'user_id' => auth()->user()->id,
                'published_at' => $request->published_at
            ]);

        if($request->tags)
        {
            $post->tags()->attach($request->tags);
        }
        
        session()->flash('success', 'Post created successfully.');

        return redirect(route('posts.index'));
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
     * Show the form for editing the specified resource.
     * Returns a view posts.create with post fields populated and category is also used to select a particular category of a post.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.create')->with('post', $post)->with('categories', Category::all())->with('tags', Tag::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->only(['title', 'description', 'published_at', 'content']);
        
        //check if request has a new file for updation
        if($request->hasFile('image'))
        {
            // Store the image in local project folder
            $image = request()->file('image');
            $imageExtension = $image->getClientOriginalExtension();
            $imageFilename = $image->getFilename();

            Storage::disk('uploads')->put($imageFilename.'.'.$imageExtension, File::get($image));
            
            //delete the old image of that post
            Storage::delete(public_path().'/images/posts/'.$post->image);

            $data['image'] = $imageFilename.'.'.$imageExtension;
        }

        if($request->tags)
        {
            $post->tags()->sync($request->tags);
        }

        $post->update($data);

        session()->flash('success', 'Post updated successfully.');
        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     * Trash a particular post.
     * We can't use router binding because we need to find a particular post to be trashed.
     *  
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        $post = Post::withTrashed()->where('id', $id)->firstOrFail();

        // check if post is already trashed so delete it permanently
        if($post->trashed()) 
        {
            // Delete the post image from local project folder
            Storage::delete($post->image);
            $post->forceDelete();
            session()->flash('success', 'Post deleted successfully.');
        }
        else
        {
            // Trash the post
            $post->delete();
            session()->flash('success', 'Post trashed successfully.');
        }

        return redirect(route('posts.index'));

    }

    /**
     * Display a list of all trashed posts
     * Returns a view a posts.index with trashed posts only
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {
        $trashed = Post::onlyTrashed()->get();
        return view('posts.index')->withPosts($trashed);
    }


    /**
     * Restore a trashed post
     * Restore a trashed post
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $post = Post::withTrashed()->where('id', $id)->firstOrFail();
        $post->restore();
        session()->flash('success', 'Post restored successfully.');
        return redirect()->back();
    }
}
