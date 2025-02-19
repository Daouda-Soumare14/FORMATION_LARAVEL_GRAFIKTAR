<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\category;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\FormPostRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function create()
{
    // Crée une nouvelle instance de modèle de Post
    $post = new Post();
    $categories = Category::select('id', 'name')->get();
    $tags = Tag::select('id', 'name')->get();

    // Retourne la vue "blog.create" en passant l'instance de Post, categories et tags à cette vue
    return view('blog.create', compact('post', 'categories', 'tags'));
}

public function store(FormPostRequest $request)
{
    // Crée un nouvel enregistrement de Post avec les données validées du formulaire
    $post = new Post();
    $post = Post::create($this->extractData($post, $request));
    $post->tags()->sync($request->validated('tags'));

    // Redirige vers la page d'index du blog avec un message de succès
    return redirect(Route('blog.index'))->with('success', 'L\'article a bien été sauvegardé');
}

public function edit(Post $post)
{
    $categories = Category::select('id', 'name')->get();
    $tags = Tag::select('id', 'name')->get();
    // Retourne la vue "blog.edit" en passant l'instance du Post à éditer
    return view('blog.edit', compact('post', 'categories', 'tags'));

    /*autre façon de faire
    return view('blog.edit', [
        'post' => $post,
        'categories' => Category::select('id', 'name')->get(),
        'tags' => Tag::select('id', 'name')->get()
    ]);*/
}

public function update(Post $post, FormPostRequest $request)
{
    $post->update($this->extractData($post, $request));
    $post->tags()->sync($request->validated('tags'));

    // Redirige vers la page d'index du blog avec un message de succès
    return redirect(Route('blog.index'))->with('success', 'L\'article a bien été modifié');
}

private function extractData(Post $post, FormRequest $request): array
{
    $data = $request->validated();
    /** @var UploadedFile|null $image */
    $image = $request->file('image');
    
    if (!$image || !$image->isValid()) {
        return $data;
    }

    if ($post->image) {
        Storage::disk('public')->delete($post->image);
    }

    $data['image'] = $image->store('blog', 'public');

    return $data;
}


public function index()
{
// one to many
    // $category = Category::find(1);
    // return $category->posts;

    // $post = Post::find(2);
    // return $post->category->name;

    // $category = Category::find(1); // pour partir d'une condition
    // $category->posts()->where('id', '>', 1)->get(); // un query bulder

    // $posts = Post::with('category')->get(); // c ce qu'on appel du Eager Loading
    // foreach ($posts as $post) {
    //     $category = $post->category?->name;
    // }

    // $category = Category::find(1);
    // $post = Post::find(4);
    // $post->category()->associate($category); // associe une category a un post donnee 
    // $post->save();

    // creation d'un utilisateur
    // User::create([
    //     'name' => 'daouda',
    //     'email' => 'daoudasoum14@gmail.com',
    //     'password' => Hash::make('soumare'),
    // ]);

    // dd(Auth::user());

// many to many
    $post = Post::find(6);
            // $post->tags()->createMany([[
            //     'name' => 'tag 1'
            // ], [
            //     'name' => 'tag 2'
            // ]]);
    //  $post = ($post->tags);
    //$post = $post->tags()->detach([1, 2]); // permet de detacher un tag a un post associé
    // $post = $post->tags()->attach(2); // permet d'attacher un tag a un post associé
    //$post = $post->tags()->sync([1, 2]);

    // Récupère tous les articles paginés
    $posts = Post::with('tags', 'category')->paginate(10);
    
    // Retourne la vue "blog.index" en passant les articles paginés à cette vue
    return view('blog.index', compact('posts'));
}

public function show(string $slug, Post $post) : RedirectResponse | View
{
    // Vérifie si le slug de l'article correspond au slug fourni dans l'URL
    if($post->slug != $slug)
    {
        // Redirige vers l'URL correcte si les slugs ne correspondent pas
        return to_route('blog.show', ['slug' => $post->slug, 'id' => $post->id]);
    }
    // Retourne la vue "blog.show" en passant l'instance de Post à cette vue
    return view('blog.show', compact('post'));
}

}

