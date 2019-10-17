<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\Category;
use App\Tag;
use Illuminate\Support\Facades\Hash;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author1 = App\User::create([
            'name' => 'Anubhav',
            'email' => 'anubhav@daffodilsw.com',
            'password' => Hash::make('password')
        ]);
        
        $author2 = App\User::create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => Hash::make('password')
        ]);
        
        $category1 = Category::create([
            'name' => 'News'
        ]);
        
        $category2 = Category::create([
            'name' => 'Marketing'
        ]);
        
        $category3 = Category::create([
            'name' => 'Partnership'
        ]);
        
        $post1 = Post::create([
            'title' => 'We relocated our office to a new designed garage',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
            'category_id' => $category1->id,
            'image' => 'posts/1.jpg',
            // Either pass as a parameter
            'user_id' => $author1->id
        ]);
        
        // Or make a post through an author
        $post2 = $author2->posts()->create([
            'title' => 'Top 5 brilliant content marketing strategies',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
            'category_id' => $category2->id,
            'image' => 'posts/2.jpg'
        ]);
        
        $post3 = $author1->posts()->create([
            'title' => 'Best practices for minimalist design with example',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
            'category_id' => $category3->id,
            'image' => 'posts/3.jpg'
        ]);
        
        $post4 = $author2->posts()->create([
            'title' => 'Congratulate and thank to Maryam for joining our team',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
            'category_id' => $category2->id,
            'image' => 'posts/4.jpg'
        ]);
        
        $tag1 = Tag::create([
            'name' => 'job'
        ]);
                
        $tag2 = Tag::create([
            'name' => 'customers'
        ]);
                        
        $tag3 = Tag::create([
            'name' => 'record'
        ]);
        
        $post1->tags()->attach([$tag1->id, $tag2->id]);
        $post2->tags()->attach([$tag2->id, $tag3->id]);
        $post3->tags()->attach([$tag1->id, $tag3->id]);
        
    }
}
