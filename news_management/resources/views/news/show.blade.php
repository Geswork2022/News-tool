@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-lg border-0 rounded overflow-hidden">
        @if($news->image)
          <div class="image-container" style="max-height: 400px; overflow: hidden;">
            <img src="{{ asset('storage/'.$news->image) }}" class="w-100 img-fluid" style="object-fit: cover; height: 400px;" alt="{{ $news->title }}">
          </div>
        @endif

        <div class="card-body p-5">
          <h2 class="mb-3 text-dark fw-bold text-center">📰 {{ $news->title }}</h2>

          <p class="text-muted text-center">
            <strong>Description :</strong> {{ $news->short_description }}
          </p>
          
          <p class="text-muted text-center">
            <strong>Produit :</strong> 
            @if($news->product)
              {{ $news->product->name }}
              {{-- Lien vers la page du produit (optionnel) si vous avez une route "products.show" --}}
              {{-- <br>
              <a href="{{ route('products.show', $news->product->id) }}">
                Voir plus sur le produit
              </a> --}}
            @else
              Aucun produit associé
            @endif
          </p>
          <hr>

          <div class="trix-content p-4 bg-light rounded border">
            {!! str_replace('/storage/app/public/', '/storage/', $news->content) !!}
          </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
          <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">⬅ Retour à la liste</a>
          <div>
            <a href="{{ route('news.edit', $news->id) }}" class="btn btn-warning">
              <i class="fas fa-edit"></i> Modifier
            </a>
            <form action="{{ route('news.destroy', $news->id) }}" method="POST" class="d-inline delete-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Supprimer
              </button>
            </form>
          </div>
        </div>
      </div>

      {{-- <pre>{{ htmlspecialchars($news->content) }}</pre> --}}
    </div>
  </div>
</div>

<style>
  .trix-content {
    max-width: 100%;
    overflow-y: auto;
  }
  .trix-content img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 10px auto;
  }
  .trix-content a {
    color: #007bff;
    text-decoration: underline;
  }
  .trix-content a:hover {
    text-decoration: none;
  }
  .trix-content figure {
    margin: 10px 0;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      if (confirm('Voulez-vous vraiment supprimer cette news ?')) {
        this.submit();
      }
    });
  });
  document.querySelectorAll('.trix-content img').forEach(img => {
    img.onerror = function() {
      console.error("L'image ne peut pas être chargée :", this.src);
      this.style.display = 'none';
    };
  });
});
</script>
@endsection
