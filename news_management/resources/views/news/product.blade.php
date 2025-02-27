@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">News pour le produit : {{ $product->name }}</h1>

    <form method="GET" action="{{ route('news.product') }}" class="mb-4">
        <div class="form-group">
            <label for="product">Choisir un produit:</label>
            <select name="product" id="product" class="form-control" onchange="this.form.submit()">
                <option value="Geswork" {{ request('product') == 'Geswork' ? 'selected' : '' }}>Geswork</option>
                <option value="Presencia" {{ request('product') == 'Presencia' ? 'selected' : '' }}>Presencia</option>
            </select>
        </div>
    </form>

    @if($news->count() > 0)
        <div class="row">
            @foreach($news as $article)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0 rounded">
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Aucune news disponible pour ce produit.</p>
    @endif

    <a href="{{ route('news.index') }}" class="btn btn-secondary mt-3">Retour à toutes les news</a>
</div>
@endsection
