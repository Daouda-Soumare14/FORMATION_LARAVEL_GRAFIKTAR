<form action="" method="post" enctype="multipart/form-data">
    <!-- Inclus un jeton CSRF pour la sécurité -->
    @csrf
    <!-- Utilise la méthode PATCH si $post->id existe (mise à jour), sinon POST (création) -->
    @method($post->id ? "PATCH" : "POST")

    <div class="form-group">
        <label for="image">Selectionner une image</label>
        <input class="form-control" type="file" name="image" id="image">
        @error('image')
            {{ $message }}
        @enderror
    </div>


    
    <div class="form-group">
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
        <!-- Etiquette pour le champ de contenu -->
        <label for="content">Content</label>
        <!-- Zone de texte pour le contenu, préremplie avec l'ancienne valeur ou la valeur actuelle -->
        <textarea class="form-control" name="content" id="content">{{ old('content', $post->content) }}</textarea>
        <!-- Affiche le message d'erreur si la validation échoue pour le contenu -->
        @error('content')
            {{ $message }}
        @enderror
    </div>

    <div class="form-group">
        <!-- Etiquette pour le champ de catégorie -->
        <label for="category">Categorie</label>
        <!-- Liste déroulante pour sélectionner une catégorie -->
        <select class="form-control" id="category" name="category_id">
            <!-- Option par défaut invitant à sélectionner une catégorie -->
            <option value="">Selectionner une categorie</option>
            <!-- Boucle sur toutes les catégories disponibles -->
            @foreach ($categories as $category)
                <!-- Sélectionne l'option si l'ancienne valeur ou la valeur actuelle correspond à l'ID de la catégorie -->
                <option @selected(old('category_id', $post->category_id) == $category->id)
                value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <!-- Affiche le message d'erreur si la validation échoue pour la catégorie -->
        @error('category_id')
            {{ $message }}
        @enderror
    </div>

    @php
        // Récupère les IDs des tags associés au post
        $tagsIds = $post->tags()->pluck('id');
    @endphp
    <div class="form-group">
        <!-- Etiquette pour le champ de tags -->
        <label for="tag">Tag</label>
        <!-- Liste déroulante multiple pour sélectionner des tags -->
        <select class="form-control" id="tag" name="tags[]" multiple>
            <!-- Boucle sur tous les tags disponibles -->
            @foreach ($tags as $tag)
                <!-- Sélectionne l'option si l'ID du tag est dans la liste des tags associés -->
                <option @selected($tagsIds->contains($tag->id))
                value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
        </select>
        <!-- Affiche le message d'erreur si la validation échoue pour les tags -->
        @error('tags')
            {{ $message }}
        @enderror
    </div>

    <button class="btn btn-primary">
        <!-- Change le texte du bouton en fonction de l'existence de $post->id -->
        @if ($post->id)
            Modifier l'article
        @else
            Creer un nouvel article
        @endif
    </button>
</form>
