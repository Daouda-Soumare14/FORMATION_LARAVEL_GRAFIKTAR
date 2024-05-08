@extends('base')

@section('title', 'Accueil du Blog')

@section('content')
    <h1>Mon blog</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @foreach ($posts as $post)
        <article>
            <h2>{{ $post->title }}</h2>
            <p>{{ $post->content }}</p>
            <p>
            <a href="{{ route('blog.show', ['slug' => $post->slug, 'post' => $post->id]) }}" class="btn btn-primary">Lire la suite</a>
            </p>
        </article>
    @endforeach

    {{ $posts->links() }}
@endsection
