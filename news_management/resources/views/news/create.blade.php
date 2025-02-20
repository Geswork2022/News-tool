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

        <!-- Contenu (Quill Editor) -->
        <div class="mb-3">
            <label for="content" class="form-label">Contenu :</label>
            <!-- Champ caché qui recevra le HTML généré par Quill -->
            <input id="content" type="hidden" name="content" value="{{ $news->content ?? '' }}">
            <!-- Conteneur pour l'éditeur Quill -->
            <div id="editor-container" style="height: 400px;">{!! $news->content ?? '' !!}</div>
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
<script>
  // Configuration de la toolbar pour Quill
  var toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'script': 'sub' }, { 'script': 'super' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    [{ 'direction': 'rtl' }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'font': [] }],
    [{ 'align': [] }],
    ['clean'],
    ['link', 'image', 'video']
  ];

  // Gestionnaire personnalisé pour l'insertion d'image
  function imageHandler() {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = () => {
      const file = input.files[0];
      if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', "{{ csrf_token() }}");

        fetch("{{ route('upload.attachment') }}", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.url) {
            let range = quill.getSelection();
            quill.insertEmbed(range.index, 'image', data.url);
          } else {
            console.error("Erreur d'upload:", data.error);
          }
        })
        .catch(error => console.error('Erreur de requête:', error));
      }
    };
  }

  // Initialisation de l'éditeur Quill
  var quill = new Quill('#editor-container', {
    theme: 'snow',
    modules: {
      toolbar: {
        container: toolbarOptions,
        handlers: {
          image: imageHandler
        }
      },
      imageResize: {}
    }
  });

  // Synchronisation du contenu de Quill avec le champ caché lors de la soumission du formulaire
  document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('content').value = quill.root.innerHTML;
  });
</script>
@endpush
