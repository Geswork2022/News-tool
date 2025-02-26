<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Gestion des News')</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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
      background-color: #416795 !important;
    }

    .navbar-custom {
      background-color: #416795 !important;
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

  <!-- CKEditor 5 -->
  <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>

  <!-- Script pour rendre le conteneur de l'éditeur redimensionnable et configurer l'adaptateur de téléchargement -->
  <script>
    $(document).ready(function(){
      if ($('#editor-container').length) {
        $('#editor-container').resizable({
          handles: "s",
          minHeight: 400,
          maxHeight: 800
        });
      }

      function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
          return new CustomUploadAdapter(loader);
        };
      }

      class CustomUploadAdapter {
        constructor(loader) {
          this.loader = loader;
        }

        upload() {
          return this.loader.file
            .then(file => new Promise((resolve, reject) => {
              this._initRequest();
              this._initListeners(resolve, reject, file);
              this._sendRequest(file);
            }));
        }

        abort() {
          if (this.xhr) {
            this.xhr.abort();
          }
        }

        _initRequest() {
          const xhr = this.xhr = new XMLHttpRequest();
          xhr.open('POST', "{{ route('upload.attachment') }}", true);
          xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
          xhr.responseType = 'json';
        }

        _initListeners(resolve, reject, file) {
          this.xhr.addEventListener('error', () => reject(file));
          this.xhr.addEventListener('abort', () => reject());
          this.xhr.addEventListener('load', () => {
            const response = this.xhr.response;
            if (!response || response.error) {
              return reject(response && response.error ? response.error : 'Upload failed');
            }
            resolve({
              default: response.url
            });
          });

          if (this.xhr.upload) {
            this.xhr.upload.addEventListener('progress', evt => {
              if (evt.lengthComputable) {
                this.loader.uploadTotal = evt.total;
                this.loader.uploaded = evt.loaded;
              }
            });
          }
        }

        _sendRequest(file) {
          const data = new FormData();
          data.append('upload', file);
          this.xhr.send(data);
        }
      }

      ClassicEditor
        .create(document.querySelector('#editor'), {
          extraPlugins: [MyCustomUploadAdapterPlugin],
        })
        .catch(error => {
          console.error(error);
        });
    });
  </script>

  @stack('scripts')
</body>
</html>
