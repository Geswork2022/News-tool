@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">✏️ Modifier la News</h1>
    
    <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Titre :</label>
            <input type="text" name="title" class="form-control" value="{{ $news->title }}" required>
        </div>

        <div class="mb-3">
            <label for="short_description" class="form-label">Description :</label>
            <input type="text" name="short_description" class="form-control" value="{{ $news->short_description }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Contenu :</label>
            <input id="content" type="hidden" name="content" value="{{ $news->content }}">
            <trix-editor input="content"></trix-editor>
        </div>

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

@push('scripts')
<!-- Importation de Trix -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<script>
document.addEventListener("trix-attachment-add", function(event) {
    let attachment = event.attachment;
    if (attachment.file) {
        console.log("🚀 Trix détecte un fichier :", attachment.file);

        let formData = new FormData();
        formData.append("file", attachment.file);
        formData.append("_token", "{{ csrf_token() }}");

        fetch("{{ route('upload.attachment') }}", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                console.log("✅ Image envoyée avec succès :", data.url);
                attachment.setAttributes({
                    url: data.url,
                    href: data.url
                });

                // Supprimer le nom et la taille affichés dans Trix
                setTimeout(() => {
                    document.querySelectorAll("figcaption").forEach(el => el.remove());
                }, 100);
            } else {
                console.error("❌ Erreur côté serveur :", data.error);
            }
        })
        .catch(error => console.error("❌ Erreur de requête Fetch :", error));
    }
});
</script>
@endpush
@endsection
