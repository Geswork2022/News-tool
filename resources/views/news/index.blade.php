@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">📰 Dernières News</h1>
        <a href="{{ route('news.create') }}" class="btn btn-lg shadow-sm" style="background-color: #494949; color: white; transition: transform 0.2s, background-color 0.2s;" onmouseover="this.style.backgroundColor='#3a3a3a'; this.style.transform='scale(1.05)'" onmouseout="this.style.backgroundColor='#494949'; this.style.transform='scale(1)'">
            ➕ Ajouter une News
        </a>
    </div>

    <!-- Formulaire de filtre par produit et catégorie -->
    <form method="GET" action="{{ route('news.index') }}" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label for="product_id" class="form-label fw-bold">Filtrer par produit</label>
                <select name="product_id" id="product_id" class="form-select shadow-sm" style="transition: background-color 0.2s;" onchange="this.form.submit()" onmouseover="this.style.backgroundColor='#f0f0f0';" onmouseout="this.style.backgroundColor='white';">
                    <option value="">-- Tous les produits --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="category_id" class="form-label fw-bold">Filtrer par catégorie</label>
                <select name="category_id" id="category_id" class="form-select shadow-sm" style="transition: background-color 0.2s;" onchange="this.form.submit()" onmouseover="this.style.backgroundColor='#f0f0f0';" onmouseout="this.style.backgroundColor='white';">
                    <option value="">-- Toutes les catégories --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="row">
        @foreach($news as $article)
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0 rounded position-relative" style="transition: transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 20px rgba(0, 0, 0, 0.2)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 1px 3px rgba(0, 0, 0, 0.1)';">
                    <a href="{{ route('news.show', $article->id) }}">
                        <img src="{{ asset('storage/'.$article->image) }}"
                             class="card-img-top"
                             alt="{{ $article->title }}"
                             style="height: 200px; object-fit: cover;">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('news.show', $article->id) }}"
                               class="text-decoration-none text-primary">
                                {{ $article->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($article->short_description, 100) }}
                        </p>
                        <p class="card-text">
                            Produit: {{ $article->product->name ?? 'Aucun produit' }}
                        </p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <small class="text-muted">
                            {{ $article->category->name ?? 'Aucune catégorie' }}
                        </small>
                        <div>
                            <a href="{{ route('news.edit', $article->id) }}"
                               class="btn btn-warning btn-sm"
                               style="transition: transform 0.2s, background-color 0.2s;"
                               onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#e0a800';"
                               onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#ffc107';">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('news.destroy', $article->id) }}"
                                  method="POST"
                                  class="delete-form d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        style="transition: transform 0.2s, background-color 0.2s;"
                                        onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#c82333';"
                                        onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#dc3545';">
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