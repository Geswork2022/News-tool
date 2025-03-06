<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Gestion des News')</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

  <style>
    .ui-resizable-s {
      height: 10px;
      background: #e0e0e0;
      border-top: 1px dashed #888;
      cursor: ns-resize;
    }

    .bg-color {
      background-color: #494949 !important;
    }

    .navbar-custom {
      background-color: #494949 !important;
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
  <style>
    .navbar-nav .nav-link {
      transition: color 0.3s ease, background-color 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      color: #ffffff;
      background-color: transparent;
      transform: translateY(-2px);
    }
  </style>

  <div id="main-content" class="container mt-4 mb-5">
    @yield('content')
  </div>

  <footer class="bg-color text-light py-4">
    <div class="container text-center">
      <p class="mb-0">&copy; {{ date('Y') }}.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.tiny.cloud/1/wticqdjmvfdwlhslw7qbagus8he5ft72h1ekenj5ayc7430m/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image | upload',
      toolbar_mode: 'floating',
      setup: function (editor) {
        editor.ui.registry.addButton('upload', {
          icon: 'upload',
          tooltip: 'Upload Attachment',
          onAction: function () {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            input.onchange = function () {
              var file = input.files[0];
              var reader = new FileReader();
              reader.onload = function (e) {
                var img = new Image();
                img.src = e.target.result;
                editor.insertContent('<img src="' + img.src + '"/>');
              };
              reader.readAsDataURL(file);
            };
          }
        });
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
