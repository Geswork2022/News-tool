@extends('layouts.app')

@section('content')
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

        <!-- Contenu (Trix) -->
        <div class="mb-3">
            <label for="content" class="form-label">Contenu :</label>
            <input id="content" type="hidden" name="content" value="{{ $news->content ?? '' }}">
            <trix-editor input="content"></trix-editor>
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

@push('scripts')
<!-- 1) Importation de Trix depuis le CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<!-- 2) Gestion de l'upload des fichiers via Trix -->
<script>
(function() {
    var HOST = "{{ route('upload.attachment') }}"; // Laravel traitera l'upload

    addEventListener("trix-attachment-add", function(event) {
        if (event.attachment.file) {
            uploadFileAttachment(event.attachment)
        }
    });

    function uploadFileAttachment(attachment) {
        uploadFile(attachment.file, setProgress, setAttributes);

        function setProgress(progress) {
            attachment.setUploadProgress(progress);
        }

        function setAttributes(attributes) {
            attachment.setAttributes(attributes);
        }
    }

    function uploadFile(file, progressCallback, successCallback) {
        let formData = new FormData();
        formData.append("file", file);
        formData.append("_token", "{{ csrf_token() }}");

        var xhr = new XMLHttpRequest();
        xhr.open("POST", HOST, true);

        xhr.upload.addEventListener("progress", function(event) {
            var progress = (event.loaded / event.total) * 100;
            progressCallback(progress);
        });

        xhr.addEventListener("load", function(event) {
            if (xhr.status === 200) {
                var json = JSON.parse(xhr.responseText);
                if (json.url) {
                    successCallback({
                        url: json.url,
                        href: json.url
                    });
                }
            }
        });

        xhr.send(formData);
    }
})();
</script>

@push('scripts')
<!-- Importation de Trix -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<script>
document.addEventListener("trix-attachment-add", function(event) {
    let attachment = event.attachment;
    if (attachment.file) {
        console.log("🚀 Trix détecte un fichier : ", attachment.file);

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
            } else {
                console.error("❌ Erreur côté serveur :", data.error);
            }
        })
        .catch(error => console.error("❌ Erreur de requête Fetch :", error));
    }
});
</script>

@endpush
