@extends('layouts.app')

@section('content')
<!-- @vite(['resources/js/editor.js']) -->
<div class="container mt-5">
    <h1 class="mb-4">
        {{ isset($news) ? '✏️ Modifier la News' : '➕ Ajouter une News' }}
    </h1>

    <form action="{{ isset($news) ? route('news.update', $news->id) : route('news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($news))
            @method('PUT')
        @endif

        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre :</label>
            <input type="text" name="title" class="form-control"
                   value="{{ $news->title ?? '' }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="short_description" class="form-label">Description :</label>
            <input type="text" name="short_description" class="form-control"
                   value="{{ $news->short_description ?? '' }}" required>
        </div>

        <!-- Contenu (CKEditor 5) -->
        <div class="mb-3">
            <label for="content" class="form-label">Contenu :</label>
            <textarea id="editor" name="content" class="form-control" rows="10" required>
                {{ $news->content ?? '' }}
            </textarea>
        </div>

        <!-- Catégorie -->
        <div class="mb-3">
            <label for="category_id" class="form-label">Catégorie :</label>
            <select name="category_id" class="form-select">
                <option value="">-- Sélectionner --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (isset($news) && $news->category_id == $category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Image principale -->
        <div class="mb-3">
            <label for="image" class="form-label">Image :</label>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- Boutons -->
        <button type="submit" class="btn btn-success">
            {{ isset($news) ? 'Mettre à jour' : 'Ajouter' }}
        </button>
        <a href="{{ route('news.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
