<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\FormPostRequest;

class BlogController extends Controller
{
    public function create()
{
    // Crée une nouvelle instance de modèle de Post
    $post = new Post();

    // Retourne la vue "blog.create" en passant l'instance de Post à cette vue
    return view('blog.create', compact('post'));
}

public function store(FormPostRequest $request)
{
    // Crée un nouvel enregistrement de Post avec les données validées du formulaire
    $post = Post::create($request->validated());

    // Redirige vers la page d'index du blog avec un message de succès
    return redirect(Route('blog.index'))->with('success', 'L\'article a bien été sauvegardé');
}

public function edit(Post $post)
{
    // Retourne la vue "blog.edit" en passant l'instance du Post à éditer
    return view('blog.edit', compact('post'));
}

public function update(Post $post, FormPostRequest $request)
{
    // Met à jour l'enregistrement du Post existant avec les données validées du formulaire
    $post->update($request->validated());

    // Redirige vers la page d'index du blog avec un message de succès
    return redirect(Route('blog.index'))->with('success', 'L\'article a bien été modifié');
}

public function index() : View
{
    // Récupère tous les articles paginés
    $posts = Post::paginate(1);
    
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

