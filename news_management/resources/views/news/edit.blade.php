@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">✏️ Modifier la News</h1>

    <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre :</label>
            <input type="text" name="title" class="form-control" value="{{ $news->title }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="short_description" class="form-label">Description :</label>
            <input type="text" name="short_description" class="form-control" value="{{ $news->short_description }}" required>
        </div>

        <!-- Contenu (CKEditor 5) -->
        <div class="mb-3">
            <label for="content" class="form-label">Contenu :</label>
            <textarea id="editor" name="content" class="form-control" rows="10" required>
                {{ $news->content }}
            </textarea>
        </div>

        <!-- Catégorie -->
        <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie :</label>
            <select name="category_id" class="form-select">
                <option value="">-- Sélectionner --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $news->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Gestion des images -->
        <div class="mb-3">
            <label for="image" class="form-label">Image :</label>
            <input type="file" name="image" class="form-control">

            <!-- Affichage de l'image existante -->
            @if($news->image)
                <div class="mt-2">
                    <p>Image actuelle :</p>
                    <img src="{{ asset('storage/' . $news->image) }}" alt="Image actuelle" width="200">
                    <input type="checkbox" name="delete_image" value="1"> Supprimer l’image
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('news.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
