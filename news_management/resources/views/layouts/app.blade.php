<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Gestion des News')</title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  
  <!-- Quill Editor CSS (v1.3.6) -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.0-dev.3/quill.snow.min.css" rel="stylesheet">
  <link href="https://unpkg.com/quill-better-table@1.2.8/dist/quill-better-table.css" rel="stylesheet">
  
  <!-- jQuery UI CSS -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
  
  <style>
    .ui-resizable-s {
      height: 10px;
      background: #e0e0e0;
      border-top: 1px dashed #888;
      cursor: ns-resize;
    }

    .bg-bordeaux {
      background-color: #800000 !important;
    }

    .navbar-custom {
      background-color: #800000 !important;
    }

    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    #main-content {
      flex: 1;
    }
  </style>

  @stack('styles')
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('news.index') }}">
        Outil de création de news
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
              aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('news.index') }}">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('news.create') }}">Ajouter une News</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenu principal -->
  <div id="main-content" class="container mt-4 mb-5">
    @yield('content')
  </div>

  <footer class="bg-bordeaux text-light py-4">
    <div class="container text-center">
      <p class="mb-0">&copy; {{ date('Y') }}.</p>
    </div>
  </footer>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- jQuery UI JS -->
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Quill JS (v1.3.6) -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

  <!-- quill-better-table -->
  <script src="https://unpkg.com/quill-better-table@1.2.9/dist/quill-better-table.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.0-dev.3/quill.min.js" type="text/javascript"></script>
  
  <!-- Module pour redimensionner les images -->
  <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
  
  <!-- Script pour rendre le conteneur de l'éditeur redimensionnable -->
  <script>
    $(document).ready(function(){
      if($('#editor-container').length){
        $('#editor-container').resizable({
          handles: "s",
          minHeight: 400,
          maxHeight: 800
        });
      }
    });
  </script>
  
  @stack('scripts')
</body>
</html>
