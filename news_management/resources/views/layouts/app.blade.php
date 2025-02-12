<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des News')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <a href="{{ route('news.index') }}" class="navbar-brand">News</a>
    </nav>
    <div class="container mt-4">
        @yield('content')
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("textarea").forEach(textarea => {
                CKEDITOR.replace(textarea.id);
            });
        });
    </script>
</body>
</html>