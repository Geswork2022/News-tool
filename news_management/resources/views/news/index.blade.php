@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">📰 Dernières News</h1>
        <a href="{{ route('news.create') }}" class="btn btn-primary btn-lg shadow-sm">➕ Ajouter une News</a>
    </div>

    <div class="row">
        @foreach($news as $article)
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded position-relative">
                <a href="{{ route('news.show', $article->id) }}">
                    <img src="{{ asset('storage/'.$article->image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                </a>
                <div class="card-body">
                    <h5 class="card-title fw-bold">
                        <a href="{{ route('news.show', $article->id) }}" class="text-decoration-none text-primary">
                            {{ $article->title }}
                        </a>
                    </h5>
                    <p class="card-text text-muted">{{ Str::limit($article->short_description, 100) }}</p>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <small class="text-muted">{{ $article->category->name ?? 'Aucune catégorie' }}</small>
                    <div>
                        <a href="{{ route('news.edit', $article->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <form action="{{ route('news.destroy', $article->id) }}" method="POST" class="delete-form d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

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
    });
</script>
@endsection
