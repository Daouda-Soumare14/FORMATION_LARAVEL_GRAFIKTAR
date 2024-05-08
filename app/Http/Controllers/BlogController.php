<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Contracts\View\View;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\BlogFilterRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Pagination\Paginator;

class BlogController extends Controller
{
    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'slug' => Str::slug($request->input('title'))
        ]);

        return redirect(Route('blog.index'))->with('success', 'l\'article a bien été sauvegarder');
    }

    public function index() : View
    {
        $posts = Post::paginate(1);
    
        return view('blog.index', compact('posts'));
    }

    public function show(string $slug, Post $post) : RedirectResponse | View
    {
        if($post->slug != $slug)
        {
            return to_route('blog.show', ['slug' => $post->slug, 'id' => $post->id]);
        }
        return view('blog.show', compact('post'));
    }
}

