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

  @stack('styles')
</head>
<body>
  <nav class="navbar navbar-dark bg-dark">
      <a href="{{ route('news.index') }}" class="navbar-brand">News</a>
  </nav>
  <div class="container mt-4">
      @yield('content')
  </div>

  <!-- Quill JS -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <!-- Module pour redimensionner les images -->
  <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
  
  @stack('scripts')
</body>
</html>
