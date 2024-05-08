@extends('base')

@section('title', $post->title)

@section('content')
    <article>
        <h2>{{ $post->title }}</h2>
        <span>{{ $post->slug }}</span>
        <p>{{ $post->content }}</p>
    </article>
@endsection
