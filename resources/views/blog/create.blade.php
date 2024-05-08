@extends('base')

@section('title', 'Creer un article')

@section('content')
    <form action="" method="post">
        @csrf
        <input type="text" name="title", value="article de demonstration">
        <textarea name="content">Contenue de demonstration</textarea>
        <button>Enregistrer</button>
    </form>
@endsection
