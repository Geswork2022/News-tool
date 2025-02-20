<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Gestion des News')</title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  
  <!-- Quill Editor CSS -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  
  <!-- jQuery UI CSS -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
  
  <style>
    /* Indication visuelle pour le handle de redimensionnement */
    .ui-resizable-s {
      height: 10px;
      background: #e0e0e0;
      border-top: 1px dashed #888;
      cursor: ns-resize;
    }

    /* Exemple de gradient pour le header */
    .navbar-custom {
      background: linear-gradient(45deg, #1e3c72, #2a5298);
    }
  </style>

  @stack('styles')
</head>
<body>
  <!-- Header avec un style "navbar-expand-lg" pour le responsive -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
      <!-- Logo (optionnel) + nom du site -->
      <a class="navbar-brand fw-bold" href="{{ route('news.index') }}">
        News
      </a>

      <!-- Bouton burger pour les écrans étroits -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
              aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Liens de navigation -->
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ms-auto">
          <!-- Lien vers l'accueil (liste des News) -->
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('news.index') }}">Accueil</a>
          </li>
          <!-- Lien pour ajouter une News -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('news.create') }}">Ajouter une News</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenu principal -->
  <div class="container mt-4">
    @yield('content')
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- jQuery UI JS -->
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
  
  <!-- Bootstrap JS (pour le burger, le responsive, etc.) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Quill JS -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  
  <!-- Module pour redimensionner les images -->
  <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
  
  <!-- Script pour rendre le conteneur de l'éditeur redimensionnable -->
  <script>
    $(document).ready(function(){
      if($('#editor-container').length){
        $('#editor-container').resizable({
          handles: "s",      // Redimensionnement par le bas uniquement
          minHeight: 400,
          maxHeight: 800
        });
      }
    });
  </script>
  
  @stack('scripts')
</body>
</html>
