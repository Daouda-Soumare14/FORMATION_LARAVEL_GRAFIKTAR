<form action="" method="post">
    @csrf
    @method($post->id ? "PATCH" : "POST")
    <div class="form-group">
        {{-- old : permet de recuperer l'ancienne valeur que l'on n'avait --}}
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $post->title) }}">
        @error('title')
            {{ $message }}
        @enderror
    </div>
    <div class="form-group">
        <label for="slug">Slug</label>
        <input class="form-control" type="text" name="slug" id="slug" value="{{ old('slug', $post->slug)}}">
        @error('slug')
            {{ $message }}
        @enderror
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control" name="content" id="content">{{ old('content', $post->content) }}</textarea> 
        @error('content')
            {{ $message }}
        @enderror
    </div>
    <button class="btn btn-primary">
        @if ($post->id)
            Modifier l'article
        @else ()
            Creer un nouvel article
        @endif
    </button>
</form>